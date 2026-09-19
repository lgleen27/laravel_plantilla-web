<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Attribute as CatalogAttribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with('categories')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create', $this->formData());
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        /*
        * Guardamos las rutas que lleguen a crearse. Si algo falla después
        * de almacenar un archivo, podremos eliminarlas en el catch.
        */
        $storedPaths = [];

        try {
            DB::transaction(function () use (
                $validated,
                $request,
                &$storedPaths
            ): void {
                $product = Product::create([
                    ...$this->productData($validated),
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ]);

                $this->syncCategories($product, $validated);
                $this->syncAttributes($product, $validated);

                /*
                * Fotografía principal del producto padre.
                */
                if ($request->hasFile('cover_image')) {
                    $this->storeProductCover(
                        $product,
                        $request->file('cover_image'),
                        $request->user()->id,
                        $storedPaths,
                    );
                }

                /*
                * Variantes y sus fotografías.
                */
                $this->storeInitialVariants(
                    $product,
                    $validated['variants'] ?? [],
                    $request,
                    $storedPaths,
                );
            });
        } catch (\Throwable $exception) {
            /*
            * Una transacción revierte registros SQL, pero no archivos.
            * Por eso eliminamos del disco los archivos que se alcanzaron
            * a guardar antes del error.
            */
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            report($exception);

            return back()
                ->withInput()
                ->with('error', 'No fue posible crear el producto. Intenta nuevamente.');
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto creado correctamente con sus variantes e imágenes.');
    }

    public function edit(Product $product): View
    {
        $product->load([
            'categories',
            'attributes.attribute',
            'attributes.option',
        ]);

        return view('admin.products.edit', [
            ...$this->formData(),
            'product' => $product,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($product, $validated, $request): void {
            $product->update([
                ...$this->productData($validated),
                'updated_by' => $request->user()->id,
            ]);

            $this->syncCategories($product, $validated);
            $this->syncAttributes($product, $validated);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    private function formData(): array
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $attributes = CatalogAttribute::query()
            ->where('is_active', true)
            ->with([
                'options' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('label'),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return compact('categories', 'attributes');
    }

    /**
     * Datos recibidos desde el formulario simplificado.
     *
     * Los productos se manejan como catálogo de cotización:
     * sin precio, sin inventario y con cotización habilitada.
     */
    private function productData(array $validated): array
    {
        return [
            'name' => $validated['name'],

            'sku' => blank($validated['sku'] ?? null)
                ? null
                : trim($validated['sku']),

            'short_description' => null,

            'description' => blank($validated['description'] ?? null)
                ? null
                : $validated['description'],

            'price' => null,
            'compare_at_price' => null,

            'track_stock' => false,
            'stock' => null,
            'allow_backorder' => true,

            'status' => $validated['status'],
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'is_quotable' => true,

            'seo_keywords' => null,
        ];
    }

    private function keywordsToArray(?string $keywords): ?array
    {
        if (blank($keywords)) {
            return null;
        }

        return collect(explode(',', $keywords))
            ->map(fn ($keyword) => trim($keyword))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Sincroniza una sola categoría para el producto.
     *
     * Se conserva category_product por compatibilidad con el catálogo actual,
     * pero la interfaz administrativa solo permitirá seleccionar una categoría.
     */
    private function syncCategories(Product $product, array $validated): void
    {
        $categoryId = (int) $validated['category_id'];

        $product->categories()->sync([
            $categoryId => [
                'is_primary' => true,
                'sort_order' => 1,
            ],
        ]);
    }

    private function syncAttributes(Product $product, array $validated): void
    {
        $attributes = CatalogAttribute::query()
            ->where('is_active', true)
            ->with('options')
            ->get()
            ->keyBy('id');

        $submittedAttributes = collect($validated['attributes'] ?? []);

        foreach ($attributes as $attribute) {
            $input = $submittedAttributes->get($attribute->id, []);

            $data = $this->attributeValueData($attribute, $input);

            if ($data === null) {
                ProductAttributeValue::query()
                    ->where('product_id', $product->id)
                    ->where('attribute_id', $attribute->id)
                    ->delete();

                continue;
            }

            $product->attributes()->updateOrCreate(
                ['attribute_id' => $attribute->id],
                $data,
            );
        }
    }

    private function attributeValueData(CatalogAttribute $attribute, array $input): ?array
    {
        $type = $attribute->type;

        if (in_array($type, ['text', 'textarea', 'url'], true)) {
            $value = trim((string) ($input['value_text'] ?? ''));

            return blank($value) ? null : [
                'attribute_option_id' => null,
                'value_text' => $value,
                'value_number' => null,
                'value_boolean' => null,
                'value_date' => null,
            ];
        }

        if (in_array($type, ['number', 'decimal'], true)) {
            $value = $input['value_number'] ?? null;

            return $value === null || $value === '' ? null : [
                'attribute_option_id' => null,
                'value_text' => null,
                'value_number' => $value,
                'value_boolean' => null,
                'value_date' => null,
            ];
        }

        if ($type === 'boolean') {
            if (! array_key_exists('value_boolean', $input)) {
                return null;
            }

            return [
                'attribute_option_id' => null,
                'value_text' => null,
                'value_number' => null,
                'value_boolean' => (bool) $input['value_boolean'],
                'value_date' => null,
            ];
        }

        if ($type === 'date') {
            $value = $input['value_date'] ?? null;

            return blank($value) ? null : [
                'attribute_option_id' => null,
                'value_text' => null,
                'value_number' => null,
                'value_boolean' => null,
                'value_date' => $value,
            ];
        }

        if ($type === 'select') {
            $optionId = $input['attribute_option_id'] ?? null;

            $isValidOption = $optionId
                && $attribute->options->contains('id', (int) $optionId);

            return ! $isValidOption ? null : [
                'attribute_option_id' => $optionId,
                'value_text' => null,
                'value_number' => null,
                'value_boolean' => null,
                'value_date' => null,
            ];
        }

        return null;
    }
    
    /**
     * Guarda la imagen de portada del producto padre.
     */
    private function storeProductCover(
        Product $product,
        \Illuminate\Http\UploadedFile $image,
        int $userId,
        array &$storedPaths,
    ): void {
        $path = $image->store(
            'products/' . $product->id . '/cover',
            'public',
        );

        $storedPaths[] = $path;

        $product->media()->create([
            'collection' => 'cover',
            'disk' => 'public',
            'path' => $path,
            'file_name' => $image->getClientOriginalName(),
            'mime_type' => $image->getMimeType(),
            'size' => $image->getSize(),
            'alt_text' => $product->name,
            'is_primary' => true,
            'sort_order' => 1,
            'created_by' => $userId,
        ]);
    }

    /**
     * Crea variantes iniciales y guarda las galerías de cada una.
     */
    private function storeInitialVariants(
        Product $product,
        array $variants,
        \Illuminate\Http\Request $request,
        array &$storedPaths,
    ): void {
        foreach ($variants as $index => $variantData) {
            /*
            * Seguridad adicional para ignorar elementos vacíos
            * si el usuario agregó y eliminó una tarjeta en el navegador.
            */
            if (blank($variantData['name'] ?? null)) {
                continue;
            }

            $variant = $product->variants()->create([
                'name' => trim($variantData['name']),
                'sku' => blank($variantData['sku'] ?? null)
                    ? null
                    : trim($variantData['sku']),
                'price' => null,
                'compare_at_price' => null,
                'track_stock' => false,
                'stock' => null,
                'allow_backorder' => true,
                'status' => $variantData['status'] ?? 'active',
                'sort_order' => $index + 1,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);

            $images = $request->file("variants.{$index}.images", []);

            $this->storeVariantImages(
                $product,
                $variant,
                $images,
                $request->user()->id,
                $storedPaths,
            );
        }
    }

    /**
     * Guarda una galería de fotografías para una variante.
     */
    private function storeVariantImages(
        Product $product,
        ProductVariant $variant,
        array $images,
        int $userId,
        array &$storedPaths,
    ): void {
        foreach ($images as $imageIndex => $image) {
            $path = $image->store(
                'products/' . $product->id . '/variants/' . $variant->id,
                'public',
            );

            $storedPaths[] = $path;

            $variant->media()->create([
                'collection' => 'gallery',
                'disk' => 'public',
                'path' => $path,
                'file_name' => $image->getClientOriginalName(),
                'mime_type' => $image->getMimeType(),
                'size' => $image->getSize(),
                'alt_text' => $product->name . ' - ' . $variant->name,
                'is_primary' => $imageIndex === 0,
                'sort_order' => $imageIndex + 1,
                'created_by' => $userId,
            ]);
        }
    }
}
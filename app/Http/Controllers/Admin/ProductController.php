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
use Illuminate\Support\Str;
use Illuminate\View\View;

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

        DB::transaction(function () use ($validated, $request): void {
            $product = Product::create([
                ...$this->productData($validated),
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);

            $this->syncCategories($product, $validated);
            $this->syncAttributes($product, $validated);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
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

    private function productData(array $validated): array
    {
        return [
            'name' => $validated['name'],
            'slug' => blank($validated['slug'] ?? null)
                ? Str::slug($validated['name'])
                : Str::slug($validated['slug']),
            'sku' => blank($validated['sku'] ?? null) ? null : $validated['sku'],
            'short_description' => blank($validated['short_description'] ?? null)
                ? null
                : $validated['short_description'],
            'description' => blank($validated['description'] ?? null)
                ? null
                : $validated['description'],
            'price' => $validated['price'] ?? null,
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'track_stock' => $validated['track_stock'],
            'stock' => $validated['track_stock'] ? $validated['stock'] : null,
            'allow_backorder' => $validated['allow_backorder'],
            'status' => $validated['status'],
            'is_featured' => $validated['is_featured'],
            'is_quotable' => $validated['is_quotable'],
            'sort_order' => $validated['sort_order'],
            'seo_title' => blank($validated['seo_title'] ?? null)
                ? null
                : $validated['seo_title'],
            'seo_description' => blank($validated['seo_description'] ?? null)
                ? null
                : $validated['seo_description'],
            'seo_keywords' => $this->keywordsToArray($validated['seo_keywords'] ?? null),
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

    private function syncCategories(Product $product, array $validated): void
    {
        $categoryIds = collect($validated['categories'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $primaryCategoryId = $validated['primary_category_id'] ?? null;

        $syncData = $categoryIds
            ->mapWithKeys(fn ($categoryId) => [
                $categoryId => [
                    'is_primary' => $primaryCategoryId !== null
                        && (int) $primaryCategoryId === $categoryId,
                    'sort_order' => 0,
                ],
            ])
            ->all();

        $product->categories()->sync($syncData);
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
}
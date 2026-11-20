<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Página de inicio
     */
    public function index()
    {
        // Productos destacados activos
        $featuredQuery = Product::with([
            'categories',
            'variants' => function ($query) {
                $query->where('status', 'active')
                    ->with([
                        'media' => function ($mediaQuery) {
                            $mediaQuery->where('collection', 'gallery')
                                ->orderBy('sort_order')
                                ->orderBy('id');
                        },
                    ]);
            },
        ])
            ->where('is_featured', true)
            ->where('status', '!=', 'draft')
            ->where('is_quotable', true);

        // Si en configuraciones se activó ocultar productos sin imagen
        if (Setting::get('catalog_only_with_images', '0') == '1') {
            $featuredQuery->where(function ($subQuery) {
                $subQuery->whereHas('variants.media')
                    ->orWhereHas('media');
            });
        }

        $featuredProducts = $featuredQuery->orderBy('featured_sort_order', 'asc') 
            ->limit(6)
            ->get();

        // Cargar categoría primaria manualmente
        foreach ($featuredProducts as $product) {
            $product->setRelation('primaryCategory', $product->categories->firstWhere('pivot.is_primary', true) ?? $product->categories->first());
        }

        // Categorías activas para la vista de inicio (Bento Grid)
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('public.home', compact('featuredProducts', 'categories'));
    }

    /**
     * Catálogo de productos (mostrando variantes como registros individuales)
     */
    public function catalog(Request $request)
    {
        // Asegurar que cualquier producto activo sin variantes tenga al menos la variante por defecto
        if (Product::where('status', '!=', 'draft')->where('is_quotable', true)->whereDoesntHave('variants')->exists()) {
            Product::where('status', '!=', 'draft')
                ->where('is_quotable', true)
                ->whereDoesntHave('variants')
                ->each(function ($p) {
                    $p->variants()->create([
                        'name' => 'Única',
                        'sku' => $p->sku,
                        'status' => 'active',
                        'sort_order' => 1,
                    ]);
                });
        }

        // Consultamos las variantes activas con sus productos asociados
        $query = ProductVariant::query()
            ->select('product_variants.*')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->where('product_variants.status', 'active')
            ->whereNull('product_variants.deleted_at')
            ->where('products.status', '!=', 'draft')
            ->where('products.is_quotable', true)
            ->whereNull('products.deleted_at')
            ->with([
                'product' => function ($productQuery) {
                    $productQuery->with([
                        'categories',
                        'media' => function ($mediaQuery) {
                            $mediaQuery->where('collection', 'gallery')
                                ->orderBy('sort_order')
                                ->orderBy('id');
                        },
                        'variants' => function ($variantsQuery) {
                            $variantsQuery->where('status', 'active')
                                ->with([
                                    'media' => function ($mediaQuery) {
                                        $mediaQuery->where('collection', 'gallery')
                                            ->orderBy('sort_order')
                                            ->orderBy('id');
                                    },
                                ]);
                        },
                    ]);
                },
                'media' => function ($mediaQuery) {
                    $mediaQuery->where('collection', 'gallery')
                        ->orderBy('sort_order')
                        ->orderBy('id');
                },
            ]);

        // Búsqueda por término (nombre de producto, variante, SKU o código de barras)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.sku', 'like', "%{$search}%")
                  ->orWhere('product_variants.name', 'like', "%{$search}%")
                  ->orWhere('product_variants.sku', 'like', "%{$search}%")
                  ->orWhere('product_variants.barcode', 'like', "%{$search}%");
            });
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $categoryId = $request->category;
            $query->whereHas('product.categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // Filtro por configuración administrativa en la base de datos: Ocultar productos sin imagen
        if (Setting::get('catalog_only_with_images', '0') == '1') {
            $query->where(function ($q) {
                $q->whereHas('media')
                  ->orWhereHas('product.media')
                  ->orWhereHas('product.variants.media');
            });
        }

        // Orden manual arrastrado por el usuario: primero orden de la categoría, luego orden manual del producto
        $products = $query
            ->orderByRaw('COALESCE((
                SELECT categories.sort_order FROM categories 
                INNER JOIN category_product ON category_product.category_id = categories.id 
                WHERE category_product.product_id = products.id 
                ORDER BY category_product.is_primary DESC, categories.sort_order ASC 
                LIMIT 1
            ), 999999) ASC')
            ->orderBy('products.sort_order', 'asc')
            ->orderBy('products.name', 'asc')
            ->orderBy('product_variants.sort_order', 'asc')
            ->orderBy('product_variants.name', 'asc')
            ->paginate(12)
            ->withQueryString();

        // Categorías para el filtro
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        // Cargar categoría primaria para cada producto
        foreach ($products as $variant) {
            $product = $variant->product;
            if ($product && ! $product->relationLoaded('primaryCategory')) {
                $primaryCategory = $product->categories?->firstWhere('pivot.is_primary', true)
                    ?? $product->categories?->first();
                $product->setRelation('primaryCategory', $primaryCategory);
            }
        }

        return view('public.catalog', compact('products', 'categories'));
    }

    /**
     * Detalle de producto
     */
    public function show(Product $product)
    {
        abort_if(
            $product->status === 'draft' || ! $product->is_quotable,
            404
        );

        $product->load([
            'categories',
            'brands',

            'variants' => function ($query) {
                $query->where('status', 'active')
                    ->with([
                        'media' => function ($mediaQuery) {
                            $mediaQuery->where('collection', 'gallery')
                                ->orderBy('sort_order')
                                ->orderBy('id');
                        },
                    ]);
            },
        ]);

        $primaryCategory = $product->categories
            ->firstWhere('pivot.is_primary', true)
            ?? $product->categories->first();

        $product->setRelation('primaryCategory', $primaryCategory);

        return view('public.product-show', compact('product'));
    }
}
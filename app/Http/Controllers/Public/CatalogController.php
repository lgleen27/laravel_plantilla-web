<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Página de inicio
     */
    public function index()
    {
        // Productos destacados activos
        $featuredProducts = Product::with([
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
            ->where('status', 'active')
            ->where('is_quotable', true)
            ->limit(6)
            ->get();

        // Cargar categoría primaria manualmente
        foreach ($featuredProducts as $product) {
            $product->setRelation('primaryCategory', $product->categories->firstWhere('pivot.is_primary', true) ?? $product->categories->first());
        }

        return view('public.home', compact('featuredProducts'));
    }

    /**
     * Catálogo de productos
     */
    public function catalog(Request $request)
    {
        $query = Product::with([
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
            ->where('status', 'active')
            ->where('is_quotable', true);

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $products = $query->orderBy('name')->paginate(12)->withQueryString();

        // Categorías para el filtro
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Cargar categoría primaria manualmente para cada producto
        foreach ($products as $product) {
            $product->setRelation('primaryCategory', $product->categories->firstWhere('pivot.is_primary', true) ?? $product->categories->first());
        }

        return view('public.catalog', compact('products', 'categories'));
    }

    /**
     * Detalle de producto
     */
    public function show(Product $product)
    {
        abort_if(
            $product->status !== 'active' || ! $product->is_quotable,
            404
        );

        $product->load([
            'categories',
            'brands',
            'attributes.attribute.options',
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
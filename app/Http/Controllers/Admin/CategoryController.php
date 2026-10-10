<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Category::query()
            ->withCount([
                'products as total_products',
                'products as available_products' => function ($q) {
                    $q->where('status', 'active');
                },
                'products as out_of_stock_products' => function ($q) {
                    $q->where('status', '!=', 'active');
                }
            ])
            // Para la lupa (modal rápido) cargamos los productos básicos
            ->with(['products' => function($q) {
                $q->select('products.id', 'name', 'status')->take(10); 
            }])
            ->orderBy('name');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(15)->appends($request->all());

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => $request->boolean('is_active'),
            'parent_id' => null, // Todas son principales ahora
            'sort_order' => Category::max('sort_order') + 1,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'No puedes eliminar esta categoría porque tiene productos asignados.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Categoría eliminada.');
    }
}
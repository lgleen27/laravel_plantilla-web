<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->with('parent')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parentCategories = Category::query()
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCategory($request);

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category): View
    {
        $excludedIds = array_merge(
            [$category->id],
            $category->descendantIds(),
        );

        $parentCategories = Category::query()
            ->whereNotIn('id', $excludedIds)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $this->validateCategory($request, $category);

        if ($validated['parent_id'] !== null) {
            $newParent = Category::find($validated['parent_id']);

            if ($newParent && ($newParent->is($category) || $category->hasDescendant($newParent))) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'parent_id' => 'No puedes seleccionar la categoría actual ni una de sus subcategorías como categoría padre.',
                    ]);
            }
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->children()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'No puedes eliminar una categoría que tiene subcategorías. Primero reasigna o elimina sus subcategorías.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    private function validateCategory(Request $request, ?Category $category = null): array
    {
        $slugRule = Rule::unique('categories', 'slug')->withoutTrashed();

        if ($category) {
            $slugRule->ignore($category);
        }

        $validated = $request->validate([
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->withoutTrashed(),
            ],
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', $slugRule],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
        ]);

        $validated['slug'] = blank($validated['slug'])
            ? Str::slug($validated['name'])
            : Str::slug($validated['slug']);

        return $validated;
    }
}
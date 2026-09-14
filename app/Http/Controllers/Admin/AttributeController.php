<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute as CatalogAttribute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttributeController extends Controller
{
    public function index(): View
    {
        $attributes = CatalogAttribute::query()
            ->withCount('options')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.attributes.index', [
            'attributes' => $attributes,
            'types' => CatalogAttribute::TYPES,
        ]);
    }

    public function create(): View
    {
        return view('admin.attributes.create', [
            'types' => CatalogAttribute::TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAttribute($request);

        CatalogAttribute::create($validated);

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', 'Atributo creado correctamente.');
    }

    public function edit(CatalogAttribute $attribute): View
    {
        $attribute->load('options');

        return view('admin.attributes.edit', [
            'attribute' => $attribute,
            'types' => CatalogAttribute::TYPES,
        ]);
    }

    public function update(Request $request, CatalogAttribute $attribute): RedirectResponse
    {
        $validated = $this->validateAttribute($request, $attribute);

        if (! in_array($validated['type'], ['select', 'multiselect'], true) && $attribute->options()->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'type' => 'No puedes cambiar el tipo mientras este atributo tenga opciones. Elimina primero sus opciones.',
                ]);
        }

        $attribute->update($validated);

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', 'Atributo actualizado correctamente.');
    }

    public function destroy(CatalogAttribute $attribute): RedirectResponse
    {
        $attribute->delete();

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', 'Atributo eliminado correctamente.');
    }

    private function validateAttribute(Request $request, ?CatalogAttribute $attribute = null): array
    {
        $codeRule = Rule::unique('attributes', 'code')->withoutTrashed();

        if ($attribute) {
            $codeRule->ignore($attribute);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'code' => ['nullable', 'string', 'max:120', $codeRule],
            'type' => ['required', Rule::in(array_keys(CatalogAttribute::TYPES))],
            'is_filterable' => ['required', 'boolean'],
            'is_required' => ['required', 'boolean'],
            'is_variant_attribute' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
        ]);

        $validated['code'] = blank($validated['code'])
            ? Str::snake(Str::ascii($validated['name']))
            : Str::snake(Str::ascii($validated['code']));

        return $validated;
    }
}
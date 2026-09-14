<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute as CatalogAttribute;
use App\Models\AttributeOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeOptionController extends Controller
{
    public function store(Request $request, CatalogAttribute $attribute): RedirectResponse
    {
        if (! $attribute->requiresOptions()) {
            abort(422, 'Este tipo de atributo no permite opciones.');
        }

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'value' => ['nullable', 'string', 'max:120'],
            'hex_code' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['required', 'boolean'],
        ]);

        $value = blank($validated['value'])
            ? Str::slug($validated['label'])
            : Str::slug($validated['value']);

        $alreadyExists = $attribute->options()
            ->where('value', $value)
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'value' => 'Ya existe una opción con ese valor para este atributo.',
                ]);
        }

        $attribute->options()->create([
            ...$validated,
            'value' => $value,
        ]);

        return redirect()
            ->route('admin.attributes.edit', $attribute)
            ->with('success', 'Opción agregada correctamente.');
    }

    public function update(Request $request, CatalogAttribute $attribute, AttributeOption $option): RedirectResponse
    {
        if ($option->attribute_id !== $attribute->id) {
            abort(404);
        }

        if (! $attribute->requiresOptions()) {
            abort(422, 'Este tipo de atributo no permite opciones.');
        }

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'value' => ['nullable', 'string', 'max:120'],
            'hex_code' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['required', 'boolean'],
        ]);

        $value = blank($validated['value'])
            ? Str::slug($validated['label'])
            : Str::slug($validated['value']);

        $alreadyExists = $attribute->options()
            ->where('value', $value)
            ->whereKeyNot($option->id)
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'value' => 'Ya existe una opción con ese valor para este atributo.',
                ]);
        }

        $option->update([
            ...$validated,
            'value' => $value,
        ]);

        return redirect()
            ->route('admin.attributes.edit', $attribute)
            ->with('success', 'Opción actualizada correctamente.');
    }

    public function destroy(CatalogAttribute $attribute, AttributeOption $option): RedirectResponse
    {
        if ($option->attribute_id !== $attribute->id) {
            abort(404);
        }

        $option->delete();

        return redirect()
            ->route('admin.attributes.edit', $attribute)
            ->with('success', 'Opción eliminada correctamente.');
    }
}
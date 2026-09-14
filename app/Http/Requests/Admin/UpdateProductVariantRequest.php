<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('variants.update') ?? false;
    }

    public function rules(): array
    {
        $variant = $this->route('variant');

        return [
            'name' => ['required', 'string', 'max:180'],
            'slug' => [
                'nullable',
                'string',
                'max:220',
                Rule::unique('product_variants', 'slug')
                    ->withoutTrashed()
                    ->ignore($variant),
            ],
            'sku' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('product_variants', 'sku')
                    ->withoutTrashed()
                    ->ignore($variant),
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('product_variants', 'barcode')
                    ->withoutTrashed()
                    ->ignore($variant),
            ],

            'price' => ['nullable', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],

            'track_stock' => ['required', 'boolean'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'allow_backorder' => ['required', 'boolean'],

            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                if ($this->boolean('track_stock') && $this->input('stock') === null) {
                    $validator->errors()->add(
                        'stock',
                        'La existencia es obligatoria cuando el control de existencias está activo.'
                    );
                }
            },
        ];
    }
}
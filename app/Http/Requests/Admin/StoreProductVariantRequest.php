<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('variants.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:180',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:220',
                Rule::unique('product_variants', 'slug')->withoutTrashed(),
            ],

            'sku' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('product_variants', 'sku')->withoutTrashed(),
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('product_variants', 'barcode')->withoutTrashed(),
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'compare_at_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'track_stock' => [
                'required',
                'boolean',
            ],

            'stock' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'allow_backorder' => [
                'required',
                'boolean',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
                'max:999999',
            ],

            /*
             * Nuevas imágenes iniciales de la variante.
             */
            'images' => [
                'nullable',
                'array',
                'max:10',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
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

    public function messages(): array
    {
        return [
            'images.max' => 'Puedes seleccionar un máximo de 10 imágenes por variante.',
            'images.*.image' => 'Cada archivo debe ser una imagen válida.',
            'images.*.mimes' => 'Las imágenes deben ser JPG, JPEG, PNG o WebP.',
            'images.*.max' => 'Cada imagen puede pesar como máximo 5 MB.',
        ];
    }
}
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('products.create') ?? false;
    }

    public function rules(): array
    {
        return [
            /*
             * Producto padre
             */
            'name' => [
                'required',
                'string',
                'max:180',
            ],

            'sku' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('products', 'sku')->withoutTrashed(),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->withoutTrashed(),
            ],

            'status' => [
                'required',
                Rule::in(['draft', 'active', 'inactive']),
            ],

            'is_featured' => [
                'required',
                'boolean',
            ],

            /*
             * Portada del producto padre.
             * Por ahora es opcional, pero se recomienda para productos activos.
             */
            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            /*
             * Variantes iniciales.
             */
            'variants' => [
                'nullable',
                'array',
                'max:20',
            ],

            'variants.*.name' => [
                'required_with:variants',
                'nullable',
                'string',
                'max:180',
            ],

            'variants.*.sku' => [
                'nullable',
                'string',
                'max:120',
                'distinct',
                Rule::unique('product_variants', 'sku')->withoutTrashed(),
            ],

            'variants.*.status' => [
                'nullable',
                Rule::in(['active', 'inactive']),
            ],

            'variants.*.images' => [
                'nullable',
                'array',
                'max:10',
            ],

            'variants.*.images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            /*
             * Atributos configurables del producto padre.
             */
            'attributes' => [
                'nullable',
                'array',
            ],

            'attributes.*.value_text' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'attributes.*.value_number' => [
                'nullable',
                'numeric',
            ],

            'attributes.*.value_boolean' => [
                'nullable',
                'boolean',
            ],

            'attributes.*.value_date' => [
                'nullable',
                'date',
            ],

            'attributes.*.attribute_option_id' => [
                'nullable',
                'integer',
                Rule::exists('attribute_options', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'cover_image.image' => 'La fotografía principal debe ser una imagen válida.',
            'cover_image.mimes' => 'La fotografía principal debe ser JPG, JPEG, PNG o WebP.',
            'cover_image.max' => 'La fotografía principal puede pesar como máximo 5 MB.',

            'variants.max' => 'Puedes registrar un máximo de 20 variantes al crear un producto.',
            'variants.*.name.required_with' => 'Cada variante debe tener un nombre.',
            'variants.*.images.max' => 'Cada variante puede tener un máximo de 10 fotografías.',
            'variants.*.images.*.image' => 'Cada archivo de variante debe ser una imagen válida.',
            'variants.*.images.*.mimes' => 'Las fotografías de variantes deben ser JPG, JPEG, PNG o WebP.',
            'variants.*.images.*.max' => 'Cada fotografía puede pesar como máximo 5 MB.',
        ];
    }
}
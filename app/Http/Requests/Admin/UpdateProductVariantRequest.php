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
            'name' => [
                'required',
                'string',
                'max:180',
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

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],


            /*
             * Nuevas imágenes opcionales.
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

    public function messages(): array
    {
        return [
            'images.max' => 'Puedes seleccionar un máximo de 10 imágenes por carga.',
            'images.*.image' => 'Cada archivo debe ser una imagen válida.',
            'images.*.mimes' => 'Las imágenes deben ser JPG, JPEG, PNG o WebP.',
            'images.*.max' => 'Cada imagen puede pesar como máximo 5 MB.',
        ];
    }
}
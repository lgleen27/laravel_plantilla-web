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
            'name' => ['required', 'string', 'max:180'],
            'sku' => ['nullable', 'string', 'max:120', Rule::unique('products', 'sku')->withoutTrashed()],
            'description' => ['nullable', 'string'],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->withoutTrashed(),
            ],
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            'is_featured' => ['required', 'boolean'],
            
            // Validaciones del nuevo formato simplificado
            'variants' => ['nullable', 'array', 'max:20'],
            'variants.*.name' => ['nullable', 'string', 'max:180'],
            'variants.*.images' => ['nullable', 'array', 'max:10'],
            'variants.*.images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:1024', // <-- Límite de 1MB (1024 KB) solicitado
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'variants.max' => 'Puedes registrar un máximo de 20 variantes.',
            'variants.*.images.max' => 'Cada variante puede tener un máximo de 10 fotografías.',
            'variants.*.images.*.image' => 'El archivo debe ser una imagen válida.',
            'variants.*.images.*.mimes' => 'Las fotos deben ser formato JPG, JPEG, PNG o WebP.',
            'variants.*.images.*.max' => 'Cada fotografía puede pesar como máximo 1 MB.',
        ];
    }
}
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('products.update') ?? false;
    }

        public function rules(): array
    {
        $product = $this->route('product');
        return [
            'name' => ['required', 'string', 'max:180'],
            'sku' => ['nullable', 'string', 'max:120', Rule::unique('products', 'sku')->ignore($product)->withoutTrashed()],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->withoutTrashed()],
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            'is_featured' => ['required', 'boolean'],

            'delete_variants' => ['nullable', 'array'],
            'delete_variants.*' => ['integer'],
            'delete_media' => ['nullable', 'array'],
            'delete_media.*' => ['integer'],

            'existing_variants' => ['nullable', 'array'],
            'existing_variants.*.id' => ['required', 'integer'],
            'existing_variants.*.name' => ['nullable', 'string', 'max:180'],
            'existing_variants.*.sku' => ['nullable', 'string', 'max:120'],
            'existing_variants.*.new_images' => ['nullable', 'array', 'max:10'],
            'existing_variants.*.new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],

            'variants' => ['nullable', 'array', 'max:20'],
            'variants.*.name' => ['nullable', 'string', 'max:180'],
            'variants.*.sku' => ['nullable', 'string', 'max:120'],
            'variants.*.images' => ['nullable', 'array', 'max:10'],
            'variants.*.images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ];
    }
}
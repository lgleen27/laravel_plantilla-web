<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
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
            'slug' => [
                'nullable',
                'string',
                'max:200',
                Rule::unique('products', 'slug')
                    ->withoutTrashed()
                    ->ignore($product),
            ],
            'sku' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('products', 'sku')
                    ->withoutTrashed()
                    ->ignore($product),
            ],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],

            'price' => ['nullable', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],

            'track_stock' => ['required', 'boolean'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'allow_backorder' => ['required', 'boolean'],

            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            'is_featured' => ['required', 'boolean'],
            'is_quotable' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],

            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],

            'categories' => ['nullable', 'array'],
            'categories.*' => [
                'integer',
                Rule::exists('categories', 'id')->withoutTrashed(),
            ],
            'primary_category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->withoutTrashed(),
            ],

            'attributes' => ['nullable', 'array'],
            'attributes.*.value_text' => ['nullable', 'string', 'max:5000'],
            'attributes.*.value_number' => ['nullable', 'numeric'],
            'attributes.*.value_boolean' => ['nullable', 'boolean'],
            'attributes.*.value_date' => ['nullable', 'date'],
            'attributes.*.attribute_option_id' => [
                'nullable',
                'integer',
                Rule::exists('attribute_options', 'id'),
            ],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                $this->validateProductRules($validator);
            },
        ];
    }

    private function validateProductRules($validator): void
    {
        if ($this->boolean('track_stock') && $this->input('stock') === null) {
            $validator->errors()->add(
                'stock',
                'La existencia es obligatoria cuando el control de existencias está activo.'
            );
        }

        $categories = collect($this->input('categories', []))
            ->map(fn ($id) => (int) $id);

        $primaryCategoryId = $this->input('primary_category_id');

        if ($primaryCategoryId !== null && ! $categories->contains((int) $primaryCategoryId)) {
            $validator->errors()->add(
                'primary_category_id',
                'La categoría principal debe estar incluida dentro de las categorías seleccionadas.'
            );
        }
    }
}
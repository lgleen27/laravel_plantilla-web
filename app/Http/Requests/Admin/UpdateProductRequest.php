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
            'name' => [
                'required',
                'string',
                'max:180',
            ],

            'sku' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('products', 'sku')
                    ->withoutTrashed()
                    ->ignore($product),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            /*
             * Solo una categoría por producto.
             */
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
             * Atributos configurables.
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
}
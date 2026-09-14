@php
    $selectedCategoryIds = old(
        'categories',
        isset($product)
            ? $product->categories->pluck('id')->map(fn ($id) => (string) $id)->all()
            : []
    );

    $primaryCategoryId = old(
        'primary_category_id',
        isset($product)
            ? optional($product->categories->firstWhere('pivot.is_primary', true))->id
            : null
    );

    $attributeValues = isset($product)
        ? $product->attributes->keyBy('attribute_id')
        : collect();

    $seoKeywords = old(
        'seo_keywords',
        isset($product) && is_array($product->seo_keywords)
            ? implode(', ', $product->seo_keywords)
            : ''
    );
@endphp

@csrf

<div class="space-y-8">
    <section>
        <h3 class="text-lg font-semibold text-gray-900">
            Información general
        </h3>

        <div class="mt-4 grid gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="name" value="Nombre del producto" />

                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('name', $product->name ?? '') }}"
                    required
                    autofocus
                />

                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sku" value="SKU o código interno (opcional)" />

                <x-text-input
                    id="sku"
                    name="sku"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('sku', $product->sku ?? '') }}"
                />

                <x-input-error :messages="$errors->get('sku')" class="mt-2" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="slug" value="Slug para URL (opcional)" />

                <x-text-input
                    id="slug"
                    name="slug"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('slug', $product->slug ?? '') }}"
                />

                <p class="mt-1 text-sm text-gray-500">
                    Si lo dejas vacío, se generará automáticamente a partir del nombre.
                </p>

                <x-input-error :messages="$errors->get('slug')" class="mt-2" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="short_description" value="Descripción corta (opcional)" />

                <textarea
                    id="short_description"
                    name="short_description"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('short_description', $product->short_description ?? '') }}</textarea>

                <x-input-error :messages="$errors->get('short_description')" class="mt-2" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="description" value="Descripción completa (opcional)" />

                <textarea
                    id="description"
                    name="description"
                    rows="7"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('description', $product->description ?? '') }}</textarea>

                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
        </div>
    </section>

    <section class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-semibold text-gray-900">
            Categorías
        </h3>

        <p class="mt-1 text-sm text-gray-600">
            Selecciona una o varias categorías. Si seleccionas categorías, elige una como principal.
        </p>

        <div class="mt-4">
            <x-input-label for="categories" value="Categorías asignadas" />

            <select
                id="categories"
                name="categories[]"
                multiple
                size="8"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(in_array((string) $category->id, $selectedCategoryIds, true))
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <p class="mt-1 text-sm text-gray-500">
                Para seleccionar varias opciones, usa Ctrl en Windows/Linux o Cmd en macOS.
            </p>

            <x-input-error :messages="$errors->get('categories')" class="mt-2" />
            <x-input-error :messages="$errors->get('categories.*')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-input-label for="primary_category_id" value="Categoría principal" />

            <select
                id="primary_category_id"
                name="primary_category_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">Sin categoría principal</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected((string) $primaryCategoryId === (string) $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <p class="mt-1 text-sm text-gray-500">
                Debe ser una de las categorías seleccionadas arriba.
            </p>

            <x-input-error :messages="$errors->get('primary_category_id')" class="mt-2" />
        </div>
    </section>

    <section class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-semibold text-gray-900">
            Precio y existencia
        </h3>

        <div class="mt-4 grid gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="price" value="Precio (opcional)" />

                <x-text-input
                    id="price"
                    name="price"
                    type="number"
                    min="0"
                    step="0.01"
                    class="mt-1 block w-full"
                    value="{{ old('price', $product->price ?? '') }}"
                />

                <p class="mt-1 text-sm text-gray-500">
                    Déjalo vacío si el producto se manejará únicamente por cotización.
                </p>

                <x-input-error :messages="$errors->get('price')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="compare_at_price" value="Precio anterior o de referencia (opcional)" />

                <x-text-input
                    id="compare_at_price"
                    name="compare_at_price"
                    type="number"
                    min="0"
                    step="0.01"
                    class="mt-1 block w-full"
                    value="{{ old('compare_at_price', $product->compare_at_price ?? '') }}"
                />

                <x-input-error :messages="$errors->get('compare_at_price')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="track_stock" value="¿Controlar existencias?" />

                <select
                    id="track_stock"
                    name="track_stock"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >
                    <option value="0" @selected((int) old('track_stock', $product->track_stock ?? 0) === 0)>
                        No
                    </option>

                    <option value="1" @selected((int) old('track_stock', $product->track_stock ?? 0) === 1)>
                        Sí
                    </option>
                </select>

                <x-input-error :messages="$errors->get('track_stock')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="stock" value="Existencia disponible" />

                <x-text-input
                    id="stock"
                    name="stock"
                    type="number"
                    min="0"
                    class="mt-1 block w-full"
                    value="{{ old('stock', $product->stock ?? '') }}"
                />

                <p class="mt-1 text-sm text-gray-500">
                    Es obligatorio únicamente si activas el control de existencias.
                </p>

                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="allow_backorder" value="¿Permitir cotizar o solicitar sin existencias?" />

                <select
                    id="allow_backorder"
                    name="allow_backorder"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >
                    <option value="0" @selected((int) old('allow_backorder', $product->allow_backorder ?? 0) === 0)>
                        No
                    </option>

                    <option value="1" @selected((int) old('allow_backorder', $product->allow_backorder ?? 0) === 1)>
                        Sí
                    </option>
                </select>

                <x-input-error :messages="$errors->get('allow_backorder')" class="mt-2" />
            </div>
        </div>
    </section>

    <section class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-semibold text-gray-900">
            Publicación y cotización
        </h3>

        <div class="mt-4 grid gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="status" value="Estado" />

                <select
                    id="status"
                    name="status"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >
                    <option value="draft" @selected(old('status', $product->status ?? 'draft') === 'draft')>
                        Borrador
                    </option>

                    <option value="active" @selected(old('status', $product->status ?? 'draft') === 'active')>
                        Activo
                    </option>

                    <option value="inactive" @selected(old('status', $product->status ?? 'draft') === 'inactive')>
                        Inactivo
                    </option>
                </select>

                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sort_order" value="Orden de visualización" />

                <x-text-input
                    id="sort_order"
                    name="sort_order"
                    type="number"
                    min="0"
                    class="mt-1 block w-full"
                    value="{{ old('sort_order', $product->sort_order ?? 0) }}"
                    required
                />

                <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="is_featured" value="¿Mostrar como producto destacado?" />

                <select
                    id="is_featured"
                    name="is_featured"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >
                    <option value="0" @selected((int) old('is_featured', $product->is_featured ?? 0) === 0)>
                        No
                    </option>

                    <option value="1" @selected((int) old('is_featured', $product->is_featured ?? 0) === 1)>
                        Sí
                    </option>
                </select>

                <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="is_quotable" value="¿Permitir solicitar cotización?" />

                <select
                    id="is_quotable"
                    name="is_quotable"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >
                    <option value="1" @selected((int) old('is_quotable', $product->is_quotable ?? 1) === 1)>
                        Sí
                    </option>

                    <option value="0" @selected((int) old('is_quotable', $product->is_quotable ?? 1) === 0)>
                        No
                    </option>
                </select>

                <x-input-error :messages="$errors->get('is_quotable')" class="mt-2" />
            </div>
        </div>
    </section>

    @if ($attributes->isNotEmpty())
        <section class="border-t border-gray-200 pt-8">
            <h3 class="text-lg font-semibold text-gray-900">
                Características configurables
            </h3>

            <p class="mt-1 text-sm text-gray-600">
                Estos campos se generan desde el módulo de atributos configurables.
            </p>

            <div class="mt-4 grid gap-6 md:grid-cols-2">
                @foreach ($attributes as $attribute)
                    @php
                        $savedValue = $attributeValues->get($attribute->id);

                        $oldText = old(
                            "attributes.{$attribute->id}.value_text",
                            $savedValue?->value_text ?? ''
                        );

                        $oldNumber = old(
                            "attributes.{$attribute->id}.value_number",
                            $savedValue?->value_number ?? ''
                        );

                        $oldBoolean = old(
                            "attributes.{$attribute->id}.value_boolean",
                            $savedValue !== null && $savedValue->value_boolean !== null
                                ? (int) $savedValue->value_boolean
                                : ''
                        );

                        $oldDate = old(
                            "attributes.{$attribute->id}.value_date",
                            $savedValue?->value_date?->format('Y-m-d') ?? ''
                        );

                        $oldOptionId = old(
                            "attributes.{$attribute->id}.attribute_option_id",
                            $savedValue?->attribute_option_id ?? ''
                        );
                    @endphp

                    <div class="@if ($attribute->type === 'textarea') md:col-span-2 @endif">
                        <x-input-label
                            :for="'attribute_'.$attribute->id"
                            :value="$attribute->name . ($attribute->is_required ? ' *' : '')"
                        />

                        @if (in_array($attribute->type, ['text', 'url'], true))
                            <x-text-input
                                :id="'attribute_'.$attribute->id"
                                :name="'attributes['.$attribute->id.'][value_text]'"
                                :type="$attribute->type === 'url' ? 'url' : 'text'"
                                class="mt-1 block w-full"
                                :value="$oldText"
                            />
                        @elseif ($attribute->type === 'textarea')
                            <textarea
                                id="attribute_{{ $attribute->id }}"
                                name="attributes[{{ $attribute->id }}][value_text]"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ $oldText }}</textarea>
                        @elseif (in_array($attribute->type, ['number', 'decimal'], true))
                            <x-text-input
                                :id="'attribute_'.$attribute->id"
                                :name="'attributes['.$attribute->id.'][value_number]'"
                                type="number"
                                step="{{ $attribute->type === 'decimal' ? '0.000001' : '1' }}"
                                class="mt-1 block w-full"
                                :value="$oldNumber"
                            />
                        @elseif ($attribute->type === 'boolean')
                            <select
                                id="attribute_{{ $attribute->id }}"
                                name="attributes[{{ $attribute->id }}][value_boolean]"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Sin especificar</option>
                                <option value="1" @selected((string) $oldBoolean === '1')>Sí</option>
                                <option value="0" @selected((string) $oldBoolean === '0')>No</option>
                            </select>
                        @elseif ($attribute->type === 'date')
                            <x-text-input
                                :id="'attribute_'.$attribute->id"
                                :name="'attributes['.$attribute->id.'][value_date]'"
                                type="date"
                                class="mt-1 block w-full"
                                :value="$oldDate"
                            />
                        @elseif ($attribute->type === 'select')
                            <select
                                id="attribute_{{ $attribute->id }}"
                                name="attributes[{{ $attribute->id }}][attribute_option_id]"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Selecciona una opción</option>

                                @foreach ($attribute->options as $option)
                                    <option
                                        value="{{ $option->id }}"
                                        @selected((string) $oldOptionId === (string) $option->id)
                                    >
                                        {{ $option->label }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif ($attribute->type === 'multiselect')
                            <div class="mt-1 rounded-md border border-yellow-300 bg-yellow-50 p-3 text-sm text-yellow-800">
                                Este atributo es de selección múltiple y se habilitará en una fase posterior.
                            </div>
                        @endif

                        <x-input-error
                            :messages="$errors->get('attributes.'.$attribute->id.'.value_text')"
                            class="mt-2"
                        />

                        <x-input-error
                            :messages="$errors->get('attributes.'.$attribute->id.'.value_number')"
                            class="mt-2"
                        />

                        <x-input-error
                            :messages="$errors->get('attributes.'.$attribute->id.'.value_boolean')"
                            class="mt-2"
                        />

                        <x-input-error
                            :messages="$errors->get('attributes.'.$attribute->id.'.value_date')"
                            class="mt-2"
                        />

                        <x-input-error
                            :messages="$errors->get('attributes.'.$attribute->id.'.attribute_option_id')"
                            class="mt-2"
                        />
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-semibold text-gray-900">
            SEO
        </h3>

        <div class="mt-4 grid gap-6">
            <div>
                <x-input-label for="seo_title" value="Título SEO (opcional)" />

                <x-text-input
                    id="seo_title"
                    name="seo_title"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('seo_title', $product->seo_title ?? '') }}"
                />

                <x-input-error :messages="$errors->get('seo_title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="seo_description" value="Descripción SEO (opcional)" />

                <textarea
                    id="seo_description"
                    name="seo_description"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('seo_description', $product->seo_description ?? '') }}</textarea>

                <x-input-error :messages="$errors->get('seo_description')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="seo_keywords" value="Palabras clave SEO (opcional)" />

                <x-text-input
                    id="seo_keywords"
                    name="seo_keywords"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ $seoKeywords }}"
                />

                <p class="mt-1 text-sm text-gray-500">
                    Sepáralas con comas. Ejemplo: equipo, industrial, catálogo.
                </p>

                <x-input-error :messages="$errors->get('seo_keywords')" class="mt-2" />
            </div>
        </div>
    </section>

    <div class="flex items-center gap-4 border-t border-gray-200 pt-8">
        <x-primary-button>
            {{ $submitLabel }}
        </x-primary-button>

        <a
            href="{{ route('admin.products.index') }}"
            class="text-sm text-gray-600 underline hover:text-gray-900"
        >
            Cancelar
        </a>
    </div>
</div>
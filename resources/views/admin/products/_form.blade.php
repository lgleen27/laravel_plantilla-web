@php
    /*
     * Aunque el producto conserve una relación belongsToMany internamente,
     * el formulario ahora trabaja con una sola categoría.
     */
    $selectedCategoryId = old(
        'category_id',
        isset($product)
            ? optional($product->categories->firstWhere('pivot.is_primary', true))->id
                ?? $product->categories->first()?->id
            : ''
    );

    $attributeValues = isset($product)
        ? $product->attributes->keyBy('attribute_id')
        : collect();
@endphp

@csrf

<div class="space-y-8">
    {{-- Información principal --}}
    <section>
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    Información del producto
                </h3>

                <p class="mt-1 text-sm text-gray-600">
                    Captura únicamente la información necesaria para mostrar y cotizar el producto.
                </p>
            </div>

            <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">
                Cotización por WhatsApp
            </span>
        </div>

        <div class="mt-5 grid gap-6 md:grid-cols-2">
            {{-- Nombre --}}
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

            {{-- SKU --}}
            <div>
                <x-input-label for="sku" value="SKU o código interno (opcional)" />

                <x-text-input
                    id="sku"
                    name="sku"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('sku', $product->sku ?? '') }}"
                />

                <p class="mt-1 text-sm text-gray-500">
                    Úsalo solo si necesitas identificar el producto internamente.
                </p>

                <x-input-error :messages="$errors->get('sku')" class="mt-2" />
            </div>

            {{-- Categoría única --}}
            <div class="md:col-span-2">
                <x-input-label for="category_id" value="Categoría" />

                <select
                    id="category_id"
                    name="category_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >
                    <option value="">
                        Selecciona una categoría
                    </option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected((string) $selectedCategoryId === (string) $category->id)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <p class="mt-1 text-sm text-gray-500">
                    Cada producto se mostrará dentro de una sola categoría.
                </p>

                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            {{-- Descripción --}}
            <div class="md:col-span-2">
                <x-input-label for="description" value="Descripción del producto (opcional)" />

                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('description', $product->description ?? '') }}</textarea>

                <p class="mt-1 text-sm text-gray-500">
                    Describe las características principales, usos, medidas o información que ayude a cotizar.
                </p>

                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
        </div>
    </section>

    {{-- Publicación --}}
    <section class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-semibold text-gray-900">
            Publicación
        </h3>

        <div class="mt-4 grid gap-6 md:grid-cols-2">
            {{-- Estado --}}
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

                <p class="mt-1 text-sm text-gray-500">
                    Solo los productos activos aparecen en el catálogo público.
                </p>

                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            {{-- Destacado --}}
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

                <p class="mt-1 text-sm text-gray-500">
                    Los productos destacados aparecen en la página de inicio.
                </p>

                <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
            </div>
        </div>
    </section>

    {{-- Atributos configurables --}}
    @if ($attributes->isNotEmpty())
        <section class="border-t border-gray-200 pt-8">
            <h3 class="text-lg font-semibold text-gray-900">
                Características y especificaciones
            </h3>

            <p class="mt-1 text-sm text-gray-600">
                Completa solo las características que correspondan a este producto.
            </p>

            <div class="mt-5 grid gap-6 md:grid-cols-2">
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
                                <option value="1" @selected((string) $oldBoolean === '1')>
                                    Sí
                                </option>
                                <option value="0" @selected((string) $oldBoolean === '0')>
                                    No
                                </option>
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

    {{-- Nota automática --}}
    <section class="border-t border-gray-200 pt-8">
        <div class="rounded-lg border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-900">
            <p class="font-semibold">
                Datos generados automáticamente
            </p>

            <p class="mt-1">
                El enlace del producto, el orden de visualización y la información SEO
                se generan automáticamente. Todos los productos se configuran para
                solicitar cotización por WhatsApp.
            </p>
        </div>
    </section>

    {{-- Acciones --}}
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
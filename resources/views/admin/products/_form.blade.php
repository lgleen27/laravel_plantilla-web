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
                    Crear publicación
                </h3>

                <p class="mt-1 text-sm text-gray-600">
                    Captura únicamente la información necesaria.
                </p>
            </div>

        </div>

        <div class="mt-5 gap-6 md:grid-cols-2">
            {{-- Nombre --}}
            <div>
                <x-input-label for="name" value="Titulo de la publicación" />

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

                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            {{-- Descripción --}}
            <div class="md:col-span-2">
                <x-input-label for="description" value="Descripción general" />

                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('description', $product->description ?? '') }}</textarea>

                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
        </div>
    </section>

    {{-- Publicación --}}
    <section class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-semibold text-gray-900">
                
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
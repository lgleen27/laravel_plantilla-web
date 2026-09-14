@csrf

<div class="space-y-8">
    <section>
        <h3 class="text-lg font-semibold text-gray-900">
            Información de la variante
        </h3>

        <p class="mt-1 text-sm text-gray-600">
            La variante representa una diferencia real del producto, como color, tamaño, acabado o presentación.
        </p>

        <div class="mt-4 grid gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="name" value="Nombre de la variante" />

                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('name', $variant->name ?? '') }}"
                    required
                    autofocus
                />

                <p class="mt-1 text-sm text-gray-500">
                    Ejemplo: Color blanco, Presentación de 20 L o Tamaño grande.
                </p>

                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sku" value="SKU o código interno (opcional)" />

                <x-text-input
                    id="sku"
                    name="sku"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('sku', $variant->sku ?? '') }}"
                />

                <x-input-error :messages="$errors->get('sku')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="barcode" value="Código de barras (opcional)" />

                <x-text-input
                    id="barcode"
                    name="barcode"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('barcode', $variant->barcode ?? '') }}"
                />

                <x-input-error :messages="$errors->get('barcode')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="slug" value="Slug para URL (opcional)" />

                <x-text-input
                    id="slug"
                    name="slug"
                    type="text"
                    class="mt-1 block w-full"
                    value="{{ old('slug', $variant->slug ?? '') }}"
                />

                <p class="mt-1 text-sm text-gray-500">
                    Si lo dejas vacío, se generará desde el nombre.
                </p>

                <x-input-error :messages="$errors->get('slug')" class="mt-2" />
            </div>
        </div>
    </section>

    <section class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-semibold text-gray-900">
            Precio y existencias
        </h3>

        <div class="mt-4 grid gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="price" value="Precio propio (opcional)" />

                <x-text-input
                    id="price"
                    name="price"
                    type="number"
                    min="0"
                    step="0.01"
                    class="mt-1 block w-full"
                    value="{{ old('price', $variant->price ?? '') }}"
                />

                <p class="mt-1 text-sm text-gray-500">
                    Si lo dejas vacío, posteriormente se podrá tomar el precio general del producto.
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
                    value="{{ old('compare_at_price', $variant->compare_at_price ?? '') }}"
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
                    <option value="0" @selected((int) old('track_stock', $variant->track_stock ?? 0) === 0)>
                        No
                    </option>
                    <option value="1" @selected((int) old('track_stock', $variant->track_stock ?? 0) === 1)>
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
                    value="{{ old('stock', $variant->stock ?? '') }}"
                />

                <p class="mt-1 text-sm text-gray-500">
                    Es obligatoria únicamente si activas el control de existencias.
                </p>

                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="allow_backorder" value="¿Permitir solicitar sin existencias?" />

                <select
                    id="allow_backorder"
                    name="allow_backorder"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required
                >
                    <option value="0" @selected((int) old('allow_backorder', $variant->allow_backorder ?? 0) === 0)>
                        No
                    </option>
                    <option value="1" @selected((int) old('allow_backorder', $variant->allow_backorder ?? 0) === 1)>
                        Sí
                    </option>
                </select>

                <x-input-error :messages="$errors->get('allow_backorder')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sort_order" value="Orden de visualización" />

                <x-text-input
                    id="sort_order"
                    name="sort_order"
                    type="number"
                    min="0"
                    class="mt-1 block w-full"
                    value="{{ old('sort_order', $variant->sort_order ?? 0) }}"
                    required
                />

                <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
            </div>
        </div>
    </section>

    <section class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-semibold text-gray-900">
            Estado
        </h3>

        <div class="mt-4 max-w-md">
            <x-input-label for="status" value="Disponibilidad de la variante" />

            <select
                id="status"
                name="status"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
            >
                <option value="active" @selected(old('status', $variant->status ?? 'active') === 'active')>
                    Activa
                </option>
                <option value="inactive" @selected(old('status', $variant->status ?? 'active') === 'inactive')>
                    Inactiva
                </option>
            </select>

            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </section>

    <div class="flex items-center gap-4 border-t border-gray-200 pt-8">
        <x-primary-button>
            {{ $submitLabel }}
        </x-primary-button>

        <a
            href="{{ route('admin.products.variants.index', $product) }}"
            class="text-sm text-gray-600 underline hover:text-gray-900"
        >
            Cancelar
        </a>
    </div>
</div>
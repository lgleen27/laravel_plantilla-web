@csrf

<div class="space-y-8">
    {{-- Información de la variante --}}
    <section>
        <h3 class="text-lg font-semibold text-gray-900">
            Información de la variante
        </h3>

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
                <x-input-label for="status" value="Estado de la variante" />

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
        </div>
    </section>

    {{-- Precio --}}
    <section class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-semibold text-gray-900">
            Precio
        </h3>

        <div class="mt-4 max-w-md">
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
                Si lo dejas vacío, se manejará por cotización.
            </p>

            <x-input-error :messages="$errors->get('price')" class="mt-2" />
        </div>
    </section>

    {{-- Fotografías --}}
    <section class="border-t border-gray-200 pt-8">
        <h3 class="text-lg font-semibold text-gray-900">
            Fotografías de la variante
        </h3>

        <p class="mt-1 text-sm text-gray-600">
            Selecciona hasta 10 imágenes. La primera se asignará como principal.
        </p>

        @if (isset($variant) && $variant->media->isNotEmpty())
            <div class="mb-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">
                            Fotografías actuales
                        </h4>

                    </div>

                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                        {{ $variant->media->count() }} imágenes
                    </span>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    @foreach ($variant->media as $image)
                        <div
                            class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm"
                            style="width: 110px;"
                        >
                            <img
                                src="{{ Storage::disk($image->disk)->url($image->path) }}"
                                alt="{{ $image->alt_text ?: $product->name . ' - ' . $variant->name }}"
                                class="block bg-gray-100 object-contain p-1"
                                style="width: 110px; height: 90px;"
                            >

                            <div class="px-2 py-2">
                                @if ($image->is_primary)
                                    <span class="text-[11px] font-semibold text-green-700">
                                        Imagen principal
                                    </span>
                                @else
                                    <span class="text-[11px] text-gray-500">
                                        Imagen secundaria
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-5">
            <x-input-label for="images" value="Selecciona las imágenes" />

            <input
                id="images"
                name="images[]"
                type="file"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                multiple
                class="mt-1 block w-full rounded-md border border-gray-300 bg-white p-2 text-sm text-gray-700"
            >

            <p class="mt-2 text-sm text-gray-500">
                Formatos permitidos: JPG, JPEG, PNG y WebP. Máximo 5 MB por archivo.
            </p>

            <x-input-error :messages="$errors->get('images')" class="mt-2" />
            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
        </div>

        {{-- Miniaturas de archivos seleccionados --}}
        <div
            id="imagesPreview"
            class="mt-5 hidden"
            style="
                display: none;
                flex-wrap: wrap;
                align-items: flex-start;
                gap: 12px;
            "
        ></div>
    </section>

    {{-- Valores requeridos por el backend actual --}}
    <input
        type="hidden"
        name="track_stock"
        value="{{ old('track_stock', $variant->track_stock ?? 0) }}"
    >

    <input
        type="hidden"
        name="allow_backorder"
        value="{{ old('allow_backorder', $variant->allow_backorder ?? 1) }}"
    >

    <input
        type="hidden"
        name="sort_order"
        value="{{ old('sort_order', $variant->sort_order ?? 0) }}"
    >

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

@if (! isset($variant))
    <script>
        (() => {
            const initializeImagePreviews = () => {
                const input = document.getElementById('images');
                const preview = document.getElementById('imagesPreview');

                if (!input || !preview || input.dataset.previewInitialized === 'true') {
                    return;
                }

                input.dataset.previewInitialized = 'true';

                let objectUrls = [];

                const clearPreview = () => {
                    objectUrls.forEach((url) => URL.revokeObjectURL(url));
                    objectUrls = [];

                    preview.replaceChildren();
                    preview.style.display = 'none';
                };

                input.addEventListener('change', () => {
                    clearPreview();

                    const files = Array.from(input.files).slice(0, 10);

                    if (files.length === 0) {
                        return;
                    }

                    const fragment = document.createDocumentFragment();

                    files.forEach((file, index) => {
                        const objectUrl = URL.createObjectURL(file);
                        objectUrls.push(objectUrl);

                        const card = document.createElement('figure');

                        card.style.margin = '0';
                        card.style.width = '110px';
                        card.style.overflow = 'hidden';
                        card.style.border = '1px solid #d1d5db';
                        card.style.borderRadius = '8px';
                        card.style.background = '#ffffff';
                        card.style.boxShadow = '0 1px 2px rgba(0, 0, 0, 0.05)';

                        const image = document.createElement('img');

                        image.src = objectUrl;
                        image.alt = `Vista previa ${index + 1}`;
                        image.style.display = 'block';
                        image.style.width = '110px';
                        image.style.height = '90px';
                        image.style.padding = '4px';
                        image.style.background = '#f3f4f6';
                        image.style.objectFit = 'contain';

                        const caption = document.createElement('figcaption');

                        caption.style.padding = '6px';
                        caption.style.fontSize = '11px';
                        caption.style.lineHeight = '1.25';
                        caption.style.color = '#4b5563';
                        caption.style.whiteSpace = 'nowrap';
                        caption.style.overflow = 'hidden';
                        caption.style.textOverflow = 'ellipsis';

                        caption.textContent = index === 0
                            ? 'Imagen principal'
                            : `Imagen ${index + 1}`;

                        card.append(image, caption);
                        fragment.appendChild(card);
                    });

                    preview.appendChild(fragment);
                    preview.style.display = 'flex';
                });

                window.addEventListener('beforeunload', clearPreview);
            };

            /*
             * Funciona tanto si el HTML ya cargó como si Blade inserta
             * el script antes de que termine de dibujar la página.
             */
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeImagePreviews);
            } else {
                initializeImagePreviews();
            }
        })();
    </script>
@endif
@extends('public.layouts.app')

@section('title', $product->seo_title ?: $product->name)

@section('content')
    @php
        $variants = $product->variants;
        $initialVariant = null;

        $previewVariant = $variants->first();
        $previewImages = $previewVariant?->media ?? collect();
        $mainImage = $previewImages->first();

        $basePrice = $product->price;
        $hasBasePrice = $basePrice !== null && $basePrice > 0;
    @endphp

    <section class="product-detail">
        <div class="container">
            <nav class="breadcrumbs" aria-label="Ruta de navegación">
                <a href="{{ route('public.home') }}">Inicio</a>
                <span class="breadcrumb-separator">•</span>

                <a href="{{ route('public.catalog') }}">Catálogo</a>

                @if($product->primaryCategory)
                    <span class="breadcrumb-separator">•</span>
                    <a href="{{ route('public.catalog', ['category' => $product->primaryCategory->id]) }}">
                        {{ $product->primaryCategory->name }}
                    </a>
                @endif

                <span class="breadcrumb-separator">•</span>
                <span>{{ $product->name }}</span>
            </nav>

            <div class="product-detail-grid">
                {{-- Galería de Imágenes --}}
                <div class="product-gallery">
                    <div class="product-main-image">
                        @if($mainImage)
                            <img
                                id="productMainImage"
                                src="{{ Storage::disk($mainImage->disk)->url($mainImage->path) }}"
                                alt="{{ $product->name }}"
                            >
                        @else
                            <div id="productImagePlaceholder" class="product-image-placeholder">
                                Sin imagen disponible
                            </div>
                        @endif
                    </div>

                    <div id="productThumbnails" class="product-thumbnails">
                        @foreach($previewImages as $index => $image)
                            @php
                                $imageUrl = Storage::disk($image->disk)->url($image->path);
                            @endphp

                            <button
                                type="button"
                                class="product-thumbnail {{ $index === 0 ? 'is-active' : '' }}"
                                data-image-url="{{ $imageUrl }}"
                                data-image-alt="{{ $product->name }} - imagen {{ $index + 1 }}"
                                aria-label="Ver imagen {{ $index + 1 }} de {{ $product->name }}"
                            >
                                <img
                                    src="{{ $imageUrl }}"
                                    alt="{{ $product->name }} - miniatura {{ $index + 1 }}"
                                >
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Información del Producto --}}
                <div class="product-info-card">
                    @if($product->primaryCategory)
                        <span class="product-detail-category">
                            {{ $product->primaryCategory->name }}
                        </span>
                    @endif

                    <h1 class="product-detail-title">
                        {{ $product->name }}
                    </h1>

                    @if($product->short_description)
                        <p class="product-detail-short-description">
                            {{ $product->short_description }}
                        </p>
                    @endif

                    <div class="product-meta-grid">
                        <div class="product-meta-item">
                            <span class="product-meta-label">SKU / Modelo</span>
                            <span id="productSku" class="product-meta-value">
                                {{ $variants->count() ? 'Selecciona una variante' : ($product->sku ?: 'No especificado') }}
                            </span>
                        </div>
                    </div>

                    <div class="product-detail-price">
                        <span class="product-detail-price-label">
                            {{ $variants->count() ? 'Precio de la variante' : 'Precio' }}
                        </span>

                        <span
                            id="productPrice"
                            class="{{ $hasBasePrice ? 'product-detail-price-value' : 'product-detail-quote-value' }}"
                        >
                            @if($variants->count())
                                Selecciona una variante
                            @elseif($hasBasePrice)
                                ${{ number_format($basePrice, 2) }}
                            @else
                                Cotizar por WhatsApp
                            @endif
                        </span>

                        <span
                            id="productAvailability"
                            class="stock-badge {{ $variants->count() ? 'is-unavailable' : ($product->isAvailable() ? '' : 'is-unavailable') }}"
                        >
                            @if($variants->count())
                                Selecciona una variante para consultar existencia
                            @else
                                {{ $product->isAvailable() ? 'Disponible para cotización' : 'Consultar disponibilidad' }}
                            @endif
                        </span>
                    </div>

                    @if($variants->count())
                        <section class="variant-section">
                            <h2>Selecciona Modelo / Capacidad:</h2>

                            <div class="variant-list" id="variantList">
                                @foreach($variants as $variant)
                                    @php
                                        $variantImages = $variant->media
                                            ->map(fn ($image) => [
                                                'url' => Storage::disk($image->disk)->url($image->path),
                                                'alt' => $product->name . ' - ' . ($variant->name ?: $variant->sku),
                                            ])
                                            ->values();

                                        $variantPrice = $variant->price ?? $product->price;
                                        $variantHasPrice = $variantPrice !== null && $variantPrice > 0;

                                        $variantAvailable = ! $variant->track_stock
                                            || $variant->stock > 0
                                            || $variant->allow_backorder;
                                    @endphp

                                    <button
                                        type="button"
                                        class="variant-option"
                                        data-variant-id="{{ $variant->id }}"
                                        data-variant-name="{{ $variant->name ?: $variant->sku }}"
                                        data-variant-sku="{{ $variant->sku ?: $product->sku ?: 'No especificado' }}"
                                        data-variant-price="{{ $variantPrice }}"
                                        data-variant-has-price="{{ $variantHasPrice ? 'true' : 'false' }}"
                                        data-variant-available="{{ $variantAvailable ? 'true' : 'false' }}"
                                        data-variant-images='@json($variantImages)'
                                    >
                                        {{ $variant->name ?: $variant->sku ?: 'Variante #' . $variant->id }}
                                    </button>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($product->brands->count())
                        <section class="brand-section">
                            <h2>Marca de interés <small style="font-weight: 400; opacity: 0.8;">(opcional)</small></h2>

                            <div class="brand-list" id="brandList">
                                @foreach($product->brands as $brand)
                                    <button
                                        type="button"
                                        class="brand-option"
                                        data-brand-id="{{ $brand->id }}"
                                        data-brand-name="{{ $brand->name }}"
                                    >
                                        {{ $brand->name }}
                                    </button>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    <button
                        type="button"
                        id="quoteButton"
                        class="quote-button"
                        {{ $variants->count() ? 'disabled' : '' }}
                    >
                        <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="30" height="30">
                            <path fill="#ffffff" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                        </svg></span>
                        <span>Solicitar cotización</span>
                    </button>

                    <p id="variantRequiredMessage" class="variant-required-message">
                        {{ $variants->count() ? 'Por favor selecciona una variante para continuar con tu cotización.' : '' }}
                    </p>

                    <p class="quote-note">
                        Se abrirá una conversación en WhatsApp con los datos del producto seleccionado.
                    </p>
                </div>
            </div>

            @if($product->description)
                <section class="product-description-section">
                    <h2>Especificaciones y Descripción</h2>

                    <div class="product-long-description">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </section>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const product = {
                name: @json($product->name),
                sku: @json($product->sku ?: 'No especificado'),
                price: @json($product->price),
                available: @json($product->isAvailable()),
                hasVariants: @json($variants->count() > 0),
            };

            const whatsappNumber = '5213781056303';

            const mainImage = document.getElementById('productMainImage');
            const imagePlaceholder = document.getElementById('productImagePlaceholder');
            const thumbnailsContainer = document.getElementById('productThumbnails');

            const variantButtons = document.querySelectorAll('.variant-option');
            const brandButtons = document.querySelectorAll('.brand-option');

            const skuElement = document.getElementById('productSku');
            const priceElement = document.getElementById('productPrice');
            const availabilityElement = document.getElementById('productAvailability');

            const quoteButton = document.getElementById('quoteButton');
            const variantRequiredMessage = document.getElementById('variantRequiredMessage');

            let selectedVariant = null;
            let selectedBrand = null;

            const formatPrice = (price) => {
                const numericPrice = Number(price);

                if (!price || Number.isNaN(numericPrice) || numericPrice <= 0) {
                    return 'Cotizar';
                }

                return new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: 'MXN',
                    minimumFractionDigits: 2,
                }).format(numericPrice);
            };

            const setMainImage = (url, alt) => {
                if (!url) return;

                const currentImage = document.getElementById('productMainImage');

                if (currentImage) {
                    if (currentImage.src === url) return;

                    currentImage.style.opacity = '0.5';

                    window.requestAnimationFrame(() => {
                        currentImage.src = url;
                        currentImage.alt = alt;

                        currentImage.onload = () => {
                            currentImage.style.opacity = '1';
                        };

                        currentImage.onerror = () => {
                            currentImage.style.opacity = '1';
                        };
                    });

                    return;
                }

                if (imagePlaceholder) {
                    imagePlaceholder.remove();
                }

                const gallery = document.querySelector('.product-main-image');

                if (gallery) {
                    const newImage = document.createElement('img');
                    newImage.id = 'productMainImage';
                    newImage.src = url;
                    newImage.alt = alt;
                    newImage.decoding = 'async';
                    gallery.appendChild(newImage);
                }
            };

            let isChangingVariant = false;
            let currentVariantId = null;

            const activateThumbnail = (button) => {
                if (!thumbnailsContainer) return;
                thumbnailsContainer.querySelectorAll('.product-thumbnail').forEach((thumbnail) => {
                    thumbnail.classList.remove('is-active');
                });
                button.classList.add('is-active');
            };

            if (thumbnailsContainer) {
                thumbnailsContainer.addEventListener('click', (event) => {
                    const thumbnail = event.target.closest('.product-thumbnail');

                    if (!thumbnail || !thumbnailsContainer.contains(thumbnail)) return;

                    setMainImage(
                        thumbnail.dataset.imageUrl,
                        thumbnail.dataset.imageAlt
                    );

                    activateThumbnail(thumbnail);
                });
            }

            const renderThumbnails = (images) => {
                if (!thumbnailsContainer) return;

                const fragment = document.createDocumentFragment();

                if (!images || images.length === 0) {
                    thumbnailsContainer.replaceChildren();
                    return;
                }

                images.forEach((image, index) => {
                    const thumbnail = document.createElement('button');
                    thumbnail.type = 'button';
                    thumbnail.className = `product-thumbnail ${index === 0 ? 'is-active' : ''}`;
                    thumbnail.dataset.imageUrl = image.url;
                    thumbnail.dataset.imageAlt = image.alt;
                    thumbnail.setAttribute('aria-label', `Ver imagen ${index + 1} de ${product.name}`);

                    const imageElement = document.createElement('img');
                    imageElement.src = image.url;
                    imageElement.alt = `${product.name} - miniatura ${index + 1}`;
                    imageElement.loading = 'lazy';
                    imageElement.decoding = 'async';

                    thumbnail.appendChild(imageElement);
                    fragment.appendChild(thumbnail);
                });

                thumbnailsContainer.replaceChildren(fragment);
            };

            variantButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    if (isChangingVariant || currentVariantId === button.dataset.variantId) return;

                    isChangingVariant = true;
                    currentVariantId = button.dataset.variantId;

                    variantButtons.forEach((variantButton) => {
                        variantButton.classList.remove('is-selected');
                    });

                    button.classList.add('is-selected');

                    let images = [];

                    try {
                        images = JSON.parse(button.dataset.variantImages || '[]');
                    } catch (error) {
                        images = [];
                    }

                    selectedVariant = {
                        id: button.dataset.variantId,
                        name: button.dataset.variantName,
                        sku: button.dataset.variantSku,
                        price: button.dataset.variantPrice,
                        hasPrice: button.dataset.variantHasPrice === 'true',
                        available: button.dataset.variantAvailable === 'true',
                    };

                    skuElement.textContent = selectedVariant.sku;

                    if (selectedVariant.hasPrice) {
                        priceElement.textContent = formatPrice(selectedVariant.price);
                        priceElement.className = 'product-detail-price-value';
                    } else {
                        priceElement.textContent = 'Cotizar por WhatsApp';
                        priceElement.className = 'product-detail-quote-value';
                    }

                    availabilityElement.textContent = selectedVariant.available
                        ? 'Disponible para cotización'
                        : 'Consultar disponibilidad';

                    availabilityElement.classList.toggle(
                        'is-unavailable',
                        !selectedVariant.available
                    );

                    quoteButton.disabled = false;
                    variantRequiredMessage.textContent = '';

                    if (images.length > 0) {
                        setMainImage(images[0].url, images[0].alt);
                    }

                    renderThumbnails(images);
                    
                    window.setTimeout(() => {
                        isChangingVariant = false;
                    }, 120);
                });
            });

            brandButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const isAlreadySelected = button.classList.contains('is-selected');

                    brandButtons.forEach((brandButton) => {
                        brandButton.classList.remove('is-selected');
                    });

                    if (isAlreadySelected) {
                        selectedBrand = null;
                        return;
                    }

                    button.classList.add('is-selected');

                    selectedBrand = {
                        id: button.dataset.brandId,
                        name: button.dataset.brandName,
                    };
                });
            });

            quoteButton.addEventListener('click', () => {
                if (product.hasVariants && !selectedVariant) {
                    variantRequiredMessage.textContent =
                        'Selecciona una variante antes de solicitar la cotización.';

                    document.getElementById('variantList')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                    });

                    return;
                }

                const priceText = selectedVariant
                    ? (selectedVariant.hasPrice ? formatPrice(selectedVariant.price) : 'Cotizar')
                    : formatPrice(product.price);

                const skuText = selectedVariant
                    ? selectedVariant.sku
                    : product.sku;

                const messageLines = [
                    'Hola, me interesa solicitar una cotización.',
                    '',
                    `Producto: ${product.name}`,
                ];

                if (selectedVariant) {
                    messageLines.push(`Variante/Modelo: ${selectedVariant.name}`);
                }

                if (selectedBrand) {
                    messageLines.push(`Marca de interés: ${selectedBrand.name}`);
                }

                messageLines.push(
                    `Precio mostrado: ${priceText}`,
                    `SKU: ${skuText}`,
                    `URL: ${window.location.href}`
                );

                const message = encodeURIComponent(messageLines.join('\n'));
                const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${message}`;

                window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
            });
        });
    </script>
@endpush
@extends('public.layouts.app')

@section('title', $product->seo_title ?: $product->name)

@section('content')
    @php
        $variants = $product->variants;

        /*
         * No seleccionamos una variante automáticamente.
         * Si el producto tiene variantes, el visitante debe elegir una
         * antes de poder cotizar.
         */
        $initialVariant = null;

        /*
         * Para mostrar algo al cargar:
         * - Si hay variantes, mostramos imágenes de la primera variante,
         *   pero aún no se considera seleccionada para cotización.
         * - Si no hay variantes, intentamos mostrar media del producto
         *   si su relación media existe.
         */
        $previewVariant = $variants->first();
        $previewImages = $previewVariant?->media ?? collect();
        $mainImage = $previewImages->first();

        $basePrice = $product->price;
        $hasBasePrice = $basePrice !== null && $basePrice > 0;

        $attributeValues = $product->attributes;
    @endphp

    <section class="product-detail">
        <div class="container">
            <nav class="breadcrumbs" aria-label="Ruta de navegación">
                <a href="{{ route('public.home') }}">Inicio</a>

                <span class="breadcrumb-separator">/</span>

                <a href="{{ route('public.catalog') }}">Catálogo</a>

                @if($product->primaryCategory)
                    <span class="breadcrumb-separator">/</span>

                    <a href="{{ route('public.catalog', ['category' => $product->primaryCategory->id]) }}">
                        {{ $product->primaryCategory->name }}
                    </a>
                @endif

                <span class="breadcrumb-separator">/</span>

                <span>{{ $product->name }}</span>
            </nav>

            <div class="product-detail-grid">
                {{-- Galería --}}
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

                {{-- Información --}}
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
                            <span class="product-meta-label">SKU</span>
                            <span id="productSku" class="product-meta-value">
                                {{ $variants->count() ? 'Selecciona una variante' : ($product->sku ?: 'No especificado') }}
                            </span>
                        </div>

                    </div>

                    <div class="product-detail-price">
                        <span class="product-detail-price-label">
                            {{ $variants->count() ? 'Precio de la variante' : 'Precio mostrado' }}
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
                                Cotizar
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

                                        /*
                                         * Se usa la regla indicada:
                                         * - Si controla stock: disponible cuando stock > 0
                                         *   o allow_backorder es verdadero.
                                         * - Si no controla stock: se considera cotizable/disponible.
                                         */
                                        $variantAvailable = ! $variant->track_stock
                                            || $variant->stock > 0
                                            || $variant->allow_backorder;
                                    @endphp

                                    <button
                                        type="button"
                                        class="variant-option"
                                        data-variant-id="{{ $variant->id }}"
                                        data-variant-name="{{ $variant->name ?: $variant->sku }}"
                                        data-variant-sku="{{ $variant->sku ?: 'No especificado' }}"
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
                            <h2>Marca de interés <small>(opcional)</small></h2>

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
                        <span>◉</span>
                        <span>Solicitar cotización por WhatsApp</span>
                    </button>

                    <p id="variantRequiredMessage" class="variant-required-message">
                        {{ $variants->count() ? 'Selecciona una variante para continuar con la cotización.' : '' }}
                    </p>

                    <p class="quote-note">
                        La cotización se abrirá directamente en WhatsApp.
                    </p>
                </div>
            </div>

            @if($product->description)
                <section class="product-description-section">
                    <h2>Descripción del producto</h2>

                    <div class="product-long-description">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </section>
            @endif

            @if($attributeValues->count())
                <section class="product-specifications-section">
                    <h2>Especificaciones</h2>

                    <div class="specifications-grid">
                        @foreach($attributeValues as $productAttributeValue)
                            @php
                                $attribute = $productAttributeValue->attribute;

                                $value = $productAttributeValue->value
                                    ?? $productAttributeValue->text_value
                                    ?? $productAttributeValue->decimal_value
                                    ?? $productAttributeValue->number_value
                                    ?? null;

                                if (is_array($value)) {
                                    $value = implode(', ', $value);
                                }

                                if (is_bool($value)) {
                                    $value = $value ? 'Sí' : 'No';
                                }
                            @endphp

                            @if($attribute && filled($value))
                                <div class="specification-item">
                                    <span class="specification-label">
                                        {{ $attribute->name }}
                                    </span>

                                    <span class="specification-value">
                                        {{ $value }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
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

            const whatsappNumber = '5210000000000';

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
            if (!url) {
                return;
            }

            const currentImage = document.getElementById('productMainImage');

            if (currentImage) {
                if (currentImage.src === url) {
                    return;
                }

                currentImage.style.opacity = '0.55';

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
                if (!thumbnailsContainer) {
                    return;
                }

                thumbnailsContainer.querySelectorAll('.product-thumbnail').forEach((thumbnail) => {
                    thumbnail.classList.remove('is-active');
                });

                button.classList.add('is-active');
            };

            /*
            * Delegación de eventos:
            * solo se registra una vez sobre el contenedor.
            * También funciona con miniaturas creadas después por renderThumbnails().
            */
            if (thumbnailsContainer) {
                thumbnailsContainer.addEventListener('click', (event) => {
                    const thumbnail = event.target.closest('.product-thumbnail');

                    if (!thumbnail || !thumbnailsContainer.contains(thumbnail)) {
                        return;
                    }

                    setMainImage(
                        thumbnail.dataset.imageUrl,
                        thumbnail.dataset.imageAlt
                    );

                    activateThumbnail(thumbnail);
                });
            }

            const renderThumbnails = (images) => {
                if (!thumbnailsContainer) {
                    return;
                }

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
                    thumbnail.setAttribute(
                        'aria-label',
                        `Ver imagen ${index + 1} de ${product.name}`
                    );

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
                    if (isChangingVariant || currentVariantId === button.dataset.variantId) {
                        return;
                    }

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
                        priceElement.textContent = 'Cotizar';
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
                    messageLines.push(`Variante: ${selectedVariant.name}`);
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
                const whatsappUrl = `https://wa.me/${5213781056303}?text=${message}`;

                window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
            });
        });
    </script>
@endpush
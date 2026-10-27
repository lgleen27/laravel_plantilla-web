@extends('public.layouts.app')

@section('title', $product->seo_title ?: $product->name)

@section('content')
    @php
        $variants = $product->variants;

        // Recopilar todas las fotos de todas las variantes
        $allThumbnails = collect();

        foreach ($variants as $variant) {
            foreach ($variant->media as $mediaItem) {
                $url = Storage::disk($mediaItem->disk)->url($mediaItem->path);
                $allThumbnails->push([
                    'media_id' => $mediaItem->id,
                    'variant_id' => $variant->id,
                    'variant_name' => $variant->name ?: $variant->sku ?: ('Variante #' . $variant->id),
                    'url' => $url,
                    'alt' => $product->name . ' - ' . ($variant->name ?: $variant->sku ?: 'Foto'),
                ]);
            }
        }

        // Si las variantes no tienen fotos, revisar fotos directas del producto
        if ($allThumbnails->isEmpty() && $product->media->isNotEmpty()) {
            foreach ($product->media as $mediaItem) {
                $url = Storage::disk($mediaItem->disk)->url($mediaItem->path);
                $allThumbnails->push([
                    'media_id' => $mediaItem->id,
                    'variant_id' => $variants->first()?->id,
                    'variant_name' => $variants->first()?->name ?? '',
                    'url' => $url,
                    'alt' => $product->name . ' - Foto',
                ]);
            }
        }

        // Deduplicar por URL en caso de fotos compartidas
        $allThumbnails = $allThumbnails->unique('url')->values();

        $requestedVariantId = request('variant');
        if ($requestedVariantId && $variants->contains('id', (int) $requestedVariantId)) {
            $initialVariantId = (int) $requestedVariantId;
            $matchingThumb = $allThumbnails->firstWhere('variant_id', $initialVariantId);
            $mainImageUrl = $matchingThumb['url'] ?? ($allThumbnails->first()['url'] ?? null);
            $mainImageAlt = $matchingThumb['alt'] ?? ($allThumbnails->first()['alt'] ?? $product->name);
        } else {
            $initialThumbnail = $allThumbnails->first();
            $mainImageUrl = $initialThumbnail['url'] ?? null;
            $mainImageAlt = $initialThumbnail['alt'] ?? $product->name;
            $initialVariantId = $initialThumbnail['variant_id'] ?? ($variants->first()?->id ?? null);
        }

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
                {{-- Galería de Imágenes Unificada con Carrusel --}}
                <div class="product-gallery">
                    <div class="product-main-image" id="productMainImageContainer">
                        @if($mainImageUrl)
                            <img
                                id="productMainImage"
                                src="{{ $mainImageUrl }}"
                                alt="{{ $mainImageAlt }}"
                                fetchpriority="high"
                                decoding="async"
                            >
                            <button type="button" class="gallery-nav-btn gallery-prev {{ count($allThumbnails) > 1 ? '' : 'is-hidden' }}" id="galleryPrevBtn" aria-label="Foto anterior">‹</button>
                            <button type="button" class="gallery-nav-btn gallery-next {{ count($allThumbnails) > 1 ? '' : 'is-hidden' }}" id="galleryNextBtn" aria-label="Siguiente foto">›</button>
                        @else
                            <div id="productImagePlaceholder" class="product-image-placeholder">
                                Sin imagen disponible
                            </div>
                        @endif
                    </div>

                    @if($allThumbnails->isNotEmpty())
                        <div class="product-thumbnails-wrapper">
                            <button type="button" class="thumb-carousel-btn {{ count($allThumbnails) > 4 ? '' : 'is-hidden' }}" id="thumbCarouselPrev" aria-label="Miniaturas anteriores">‹</button>

                            <div id="productThumbnails" class="product-thumbnails">
                                @foreach($allThumbnails as $index => $item)
                                    <button
                                        type="button"
                                        class="product-thumbnail {{ $index === 0 ? 'is-active' : '' }}"
                                        data-image-url="{{ $item['url'] }}"
                                        data-image-alt="{{ $item['alt'] }}"
                                        data-variant-id="{{ $item['variant_id'] }}"
                                        aria-label="Ver {{ $item['alt'] }}"
                                    >
                                        <img
                                            src="{{ $item['url'] }}"
                                            alt="{{ $item['alt'] }}"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                        @if($item['variant_name'] && $variants->count() > 1)
                                            <span class="thumb-variant-tag" title="{{ $item['variant_name'] }}">{{ $item['variant_name'] }}</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>

                            <button type="button" class="thumb-carousel-btn {{ count($allThumbnails) > 4 ? '' : 'is-hidden' }}" id="thumbCarouselNext" aria-label="Siguientes miniaturas">›</button>
                        </div>
                    @endif
                </div>

                {{-- Información Principal del Producto (A Primera Vista) --}}
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

                    {{-- Barra Compacta de Precio, SKU y Disponibilidad --}}
                    <div class="product-price-meta-bar">
                        <div class="price-side">
                            <span class="price-sublabel" id="priceSublabel">
                                {{ $variants->count() ? 'Precio' : 'Precio' }}
                            </span>
                            <div id="productPrice" class="{{ $hasBasePrice ? 'product-price-val' : 'product-quote-val' }}">
                                @if($hasBasePrice)
                                    ${{ number_format($basePrice, 2) }}
                                @else
                                    Cotizar por WhatsApp
                                @endif
                            </div>
                        </div>

                        <div class="meta-side">
                            <div class="meta-item">
                                <span class="meta-label">SKU:</span>
                                <span id="productSku" class="meta-val">
                                    {{ $product->sku ?: 'No especificado' }}
                                </span>
                            </div>

                            <span
                                id="productAvailability"
                                class="stock-badge {{ $product->isAvailable() ? '' : 'is-unavailable' }}"
                            >
                                {{ $product->isAvailable() ? 'Disponible para cotización' : 'Consultar existencia' }}
                            </span>
                        </div>
                    </div>

                    {{-- Selección de Variantes --}}
                    @if($variants->count())
                        <section class="variant-section">
                            <h2>Selecciona Variante / Modelo:</h2>

                            <div class="variant-list" id="variantList">
                                @foreach($variants as $variant)
                                    @php
                                        $variantPrice = $variant->price ?? $product->price;
                                        $variantHasPrice = $variantPrice !== null && $variantPrice > 0;

                                        $variantAvailable = ! $variant->track_stock
                                            || $variant->stock > 0
                                            || $variant->allow_backorder;

                                        $variantFirstThumb = $allThumbnails->firstWhere('variant_id', $variant->id);
                                        $variantFirstThumbUrl = $variantFirstThumb['url'] ?? null;
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
                                        data-variant-first-image="{{ $variantFirstThumbUrl }}"
                                    >
                                        {{ $variant->name ?: $variant->sku ?: 'Variante #' . $variant->id }}
                                    </button>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- Marca opcional --}}
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

                    {{-- Botón de Cotización --}}
                    <button
                        type="button"
                        id="quoteButton"
                        class="quote-button"
                    >
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="28" height="28">
                                <path fill="#ffffff" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                            </svg>
                        </span>
                        <span>Solicitar cotización por WhatsApp</span>
                    </button>

                    <p id="variantRequiredMessage" class="variant-required-message"></p>

                    <p class="quote-note">
                        Se abrirá una conversación en WhatsApp con los datos del producto y variante seleccionada.
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

            const whatsappNumber = '{{ $siteSettings['whatsapp_number'] ?? '5213780000000' }}';

            const mainImageContainer = document.getElementById('productMainImageContainer');
            const galleryPrevBtn = document.getElementById('galleryPrevBtn');
            const galleryNextBtn = document.getElementById('galleryNextBtn');
            const thumbCarouselPrev = document.getElementById('thumbCarouselPrev');
            const thumbCarouselNext = document.getElementById('thumbCarouselNext');
            const thumbnailsContainer = document.getElementById('productThumbnails');
            const imagePlaceholder = document.getElementById('productImagePlaceholder');

            const variantButtons = document.querySelectorAll('.variant-option');
            const brandButtons = document.querySelectorAll('.brand-option');

            const skuElement = document.getElementById('productSku');
            const priceElement = document.getElementById('productPrice');
            const availabilityElement = document.getElementById('productAvailability');

            const quoteButton = document.getElementById('quoteButton');
            const variantRequiredMessage = document.getElementById('variantRequiredMessage');

            let selectedVariant = null;
            let selectedBrand = null;
            let isSyncing = false;

            const formatPrice = (price) => {
                const numericPrice = Number(price);

                if (!price || Number.isNaN(numericPrice) || numericPrice <= 0) {
                    return 'Cotizar por WhatsApp';
                }

                return new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: 'MXN',
                    minimumFractionDigits: 2,
                }).format(numericPrice);
            };

            const setMainImage = (url, alt, direction = null) => {
                if (!url) return;

                let currentImage = document.getElementById('productMainImage');

                if (currentImage) {
                    if (currentImage.src === url) return;

                    currentImage.style.opacity = '0.35';
                    if (direction === 'next') {
                        currentImage.style.transform = 'translateX(-12px)';
                    } else if (direction === 'prev') {
                        currentImage.style.transform = 'translateX(12px)';
                    }

                    window.requestAnimationFrame(() => {
                        currentImage.src = url;
                        currentImage.alt = alt;

                        const onFinish = () => {
                            currentImage.style.opacity = '1';
                            currentImage.style.transform = 'translateX(0)';
                        };

                        if (currentImage.complete) {
                            onFinish();
                        } else {
                            currentImage.onload = onFinish;
                            currentImage.onerror = onFinish;
                        }
                    });

                    return;
                }

                if (imagePlaceholder) {
                    imagePlaceholder.remove();
                }

                if (mainImageContainer) {
                    const newImage = document.createElement('img');
                    newImage.id = 'productMainImage';
                    newImage.src = url;
                    newImage.alt = alt;
                    newImage.decoding = 'async';
                    mainImageContainer.insertBefore(newImage, mainImageContainer.firstChild);
                }
            };

            const activateThumbnail = (thumbnailEl) => {
                if (!thumbnailsContainer || !thumbnailEl) return;
                thumbnailsContainer.querySelectorAll('.product-thumbnail').forEach((thumb) => {
                    thumb.classList.remove('is-active');
                });
                thumbnailEl.classList.add('is-active');
            };

            const checkCarouselControls = () => {
                if (!thumbnailsContainer || !thumbCarouselPrev || !thumbCarouselNext) return;
                const canScroll = thumbnailsContainer.scrollWidth > (thumbnailsContainer.clientWidth + 6);
                thumbCarouselPrev.classList.toggle('is-hidden', !canScroll);
                thumbCarouselNext.classList.toggle('is-hidden', !canScroll);
            };

            // Selección de variante (sincronización bidireccional)
            const selectVariant = (variantId, shouldSyncGallery = true) => {
                if (!variantId) return;

                const targetButton = Array.from(variantButtons).find(btn => btn.dataset.variantId == variantId);
                if (!targetButton) return;

                variantButtons.forEach(btn => btn.classList.remove('is-selected'));
                targetButton.classList.add('is-selected');

                selectedVariant = {
                    id: targetButton.dataset.variantId,
                    name: targetButton.dataset.variantName,
                    sku: targetButton.dataset.variantSku,
                    price: targetButton.dataset.variantPrice,
                    hasPrice: targetButton.dataset.variantHasPrice === 'true',
                    available: targetButton.dataset.variantAvailable === 'true',
                };

                skuElement.textContent = selectedVariant.sku;

                if (selectedVariant.hasPrice) {
                    priceElement.textContent = formatPrice(selectedVariant.price);
                    priceElement.className = 'product-price-val';
                } else {
                    priceElement.textContent = 'Cotizar por WhatsApp';
                    priceElement.className = 'product-quote-val';
                }

                availabilityElement.textContent = selectedVariant.available
                    ? 'Disponible para cotización'
                    : 'Consultar existencia';

                availabilityElement.classList.toggle('is-unavailable', !selectedVariant.available);

                quoteButton.disabled = false;
                variantRequiredMessage.textContent = '';

                // Si se solicitó sincronizar la galería al cambiar variante
                if (shouldSyncGallery && thumbnailsContainer) {
                    const matchingThumb = thumbnailsContainer.querySelector(`.product-thumbnail[data-variant-id="${variantId}"]`);
                    if (matchingThumb) {
                        setMainImage(matchingThumb.dataset.imageUrl, matchingThumb.dataset.imageAlt);
                        activateThumbnail(matchingThumb);
                        matchingThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    }
                }
            };

            // Navegación de imágenes por índice
            const showImageAtIndex = (index, direction = 'next') => {
                const thumbnails = thumbnailsContainer?.querySelectorAll('.product-thumbnail');
                if (!thumbnails || thumbnails.length <= 1) return;

                if (index < 0) index = thumbnails.length - 1;
                if (index >= thumbnails.length) index = 0;

                const targetThumb = thumbnails[index];
                if (!targetThumb) return;

                setMainImage(targetThumb.dataset.imageUrl, targetThumb.dataset.imageAlt, direction);
                activateThumbnail(targetThumb);
                targetThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });

                // Sincronizar variante si la foto pertenece a una variante
                if (targetThumb.dataset.variantId) {
                    selectVariant(targetThumb.dataset.variantId, false);
                }
            };

            // Eventos en miniaturas (clic en foto selecciona variante automáticamente)
            if (thumbnailsContainer) {
                thumbnailsContainer.addEventListener('click', (event) => {
                    const thumb = event.target.closest('.product-thumbnail');
                    if (!thumb || !thumbnailsContainer.contains(thumb)) return;

                    setMainImage(thumb.dataset.imageUrl, thumb.dataset.imageAlt);
                    activateThumbnail(thumb);
                    thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });

                    if (thumb.dataset.variantId) {
                        selectVariant(thumb.dataset.variantId, false);
                    }
                });
            }

            // Flechas de navegación en la imagen principal
            if (galleryPrevBtn) {
                galleryPrevBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const thumbnails = thumbnailsContainer?.querySelectorAll('.product-thumbnail');
                    if (!thumbnails || thumbnails.length <= 1) return;
                    let currentIndex = Array.from(thumbnails).findIndex(t => t.classList.contains('is-active'));
                    if (currentIndex === -1) currentIndex = 0;
                    showImageAtIndex(currentIndex - 1, 'prev');
                });
            }

            if (galleryNextBtn) {
                galleryNextBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const thumbnails = thumbnailsContainer?.querySelectorAll('.product-thumbnail');
                    if (!thumbnails || thumbnails.length <= 1) return;
                    let currentIndex = Array.from(thumbnails).findIndex(t => t.classList.contains('is-active'));
                    if (currentIndex === -1) currentIndex = 0;
                    showImageAtIndex(currentIndex + 1, 'next');
                });
            }

            // Flechas del carrusel de miniaturas
            if (thumbCarouselPrev && thumbnailsContainer) {
                thumbCarouselPrev.addEventListener('click', () => {
                    thumbnailsContainer.scrollBy({ left: -160, behavior: 'smooth' });
                });
            }

            if (thumbCarouselNext && thumbnailsContainer) {
                thumbCarouselNext.addEventListener('click', () => {
                    thumbnailsContainer.scrollBy({ left: 160, behavior: 'smooth' });
                });
            }

            // Botones de variante
            variantButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    selectVariant(button.dataset.variantId, true);
                });
            });

            // Gesto táctil (Swipe) para pantallas táctiles y celulares
            if (mainImageContainer) {
                let touchStartX = 0;
                let touchStartY = 0;
                let touchEndX = 0;
                let touchEndY = 0;
                let isSwiping = false;

                mainImageContainer.addEventListener('touchstart', (e) => {
                    if (e.touches.length !== 1) return;
                    touchStartX = e.touches[0].clientX;
                    touchStartY = e.touches[0].clientY;
                    touchEndX = touchStartX;
                    touchEndY = touchStartY;
                    isSwiping = true;
                }, { passive: true });

                mainImageContainer.addEventListener('touchmove', (e) => {
                    if (!isSwiping || e.touches.length !== 1) return;
                    touchEndX = e.touches[0].clientX;
                    touchEndY = e.touches[0].clientY;
                }, { passive: true });

                mainImageContainer.addEventListener('touchend', () => {
                    if (!isSwiping) return;
                    isSwiping = false;

                    const diffX = touchEndX - touchStartX;
                    const diffY = touchEndY - touchStartY;
                    const minSwipeDistance = 35;

                    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > minSwipeDistance) {
                        const thumbnails = thumbnailsContainer?.querySelectorAll('.product-thumbnail');
                        if (!thumbnails || thumbnails.length <= 1) return;

                        let currentIndex = Array.from(thumbnails).findIndex(t => t.classList.contains('is-active'));
                        if (currentIndex === -1) currentIndex = 0;

                        if (diffX < 0) {
                            showImageAtIndex(currentIndex + 1, 'next');
                        } else {
                            showImageAtIndex(currentIndex - 1, 'prev');
                        }
                    }
                }, { passive: true });
            }

            // Botones de marca opcional
            brandButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const isAlreadySelected = button.classList.contains('is-selected');

                    brandButtons.forEach(btn => btn.classList.remove('is-selected'));

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

            // Botón de WhatsApp
            quoteButton.addEventListener('click', () => {
                if (product.hasVariants && !selectedVariant) {
                    variantRequiredMessage.textContent = 'Selecciona una variante antes de solicitar la cotización.';
                    document.getElementById('variantList')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                    });
                    return;
                }

                const priceText = selectedVariant
                    ? (selectedVariant.hasPrice ? formatPrice(selectedVariant.price) : 'Cotizar por WhatsApp')
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

            // Selección inicial al cargar
            const initialVariantId = @json($initialVariantId);
            if (initialVariantId) {
                selectVariant(initialVariantId, true);
            }

            // Inicializar visibilidad de flechas del carrusel
            checkCarouselControls();
            window.addEventListener('resize', checkCarouselControls);
        });
    </script>
@endpush

@extends('public.layouts.app')

@section('title', 'Catálogo de Refrigeración y Productos')

@section('content')
    <section class="page-banner">
        <div class="container">
            <h1>Catálogo de Productos ❄</h1>

            <p>
                Encuentra congeladores, refrigeradores comerciales, neveras y mobiliario 
                para equipar tu negocio con la máxima eficiencia.
            </p>
        </div>
    </section>

    <section class="section section-soft">
        <div class="container">
            <div class="filter-box">
                <form action="{{ route('public.catalog') }}"
                      method="GET"
                      class="filter-form">

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Buscar producto o modelo..."
                           class="form-control">

                    <select name="category" class="form-control">
                        <option value="">Todas las categorías</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="filter-button">
                        Buscar
                    </button>

                    @if(request('search') || request('category'))
                        <a href="{{ route('public.catalog') }}"
                           class="clear-button">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            @if($products->count())
                <p class="results-count">
                    Mostrando <strong>{{ $products->total() }}</strong> productos disponibles
                </p>

                <div class="product-grid">
                    @foreach($products as $product)
                        @php
                            $firstVariant = $product->variants->first();
                            $firstImage = $firstVariant?->media->first();

                            $price = $firstVariant?->price ?? $product->price;
                            $hasPrice = $price !== null && $price > 0;
                            $isAvailable = $product->isAvailable();

                            $imageUrl = $firstImage
                                ? \Storage::disk($firstImage->disk)->url($firstImage->path)
                                : 'https://via.placeholder.com/600x450/e0f2fe/0369a1?text=Sin+Imagen';
                        @endphp

                        <article class="product-card">
                            <a href="{{ route('public.product.show', $product->slug) }}">
                                <div class="product-image">
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy">

                                    @if($product->is_featured)
                                        <span class="product-badge">❄ Destacado</span>
                                    @endif

                                    @if(!$isAvailable)
                                        <span class="product-badge badge-danger">Agotado</span>
                                    @endif
                                </div>

                                <div class="product-body">
                                    <p class="product-category">
                                        {{ $product->primaryCategory?->name ?? 'Refrigeración' }}
                                    </p>

                                    <h2 class="product-title">
                                        {{ $product->name }}
                                    </h2>

                                    <p class="product-description">
                                        {{ $product->short_description ?: 'Equipo con alto rendimiento y durabilidad.' }}
                                    </p>

                                    <div class="product-footer">
                                        <div>
                                            <span class="price-label">
                                                {{ $hasPrice ? 'Precio desde' : 'Disponibilidad' }}
                                            </span>

                                            @if($hasPrice)
                                                <span class="price">
                                                    ${{ number_format($price, 2) }}
                                                </span>
                                            @else
                                                <span class="quote-price">Cotizar</span>
                                            @endif
                                        </div>

                                        <div>
                                            <span class="status-label">Estado</span>

                                            <span class="status {{ $isAvailable ? 'available' : 'unavailable' }}">
                                                {{ $isAvailable ? '✓ Disponible' : '✕ Agotado' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                @if($products->hasPages())
                    <div class="pagination">
                        @if(!$products->onFirstPage())
                            <a href="{{ $products->previousPageUrl() }}">
                                ← Anterior
                            </a>
                        @endif

                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if($page == $products->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}">
                                Siguiente →
                            </a>
                        @endif
                    </div>
                @endif
            @else
                <div class="section-heading" style="padding: 40px 0;">
                    <h2>No encontramos resultados ❄</h2>
                    <p>
                        Intenta con otro término de búsqueda o selecciona una categoría diferente.
                    </p>

                    <br>

                    <a href="{{ route('public.catalog') }}" class="primary-button">
                        Ver todos los productos
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
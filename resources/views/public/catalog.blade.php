@extends('public.layouts.app')

@section('title', 'Catálogo')

@section('content')
    <section class="page-banner">
        <div class="container">
            <h1>Catálogo de productos</h1>

            <p>
                Encuentra congeladores, refrigeradores, muebles y soluciones
                para equipar tu negocio y transformar tu hogar.
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
                           placeholder="Buscar por nombre..."
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
                    <strong>{{ $products->total() }}</strong>
                    productos encontrados
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
                                : 'https://via.placeholder.com/600x450/e2e8f0/475569?text=Sin+Imagen';
                        @endphp

                        <article class="product-card">
                            <a href="{{ route('public.product.show', $product->slug) }}">
                                <div class="product-image">
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}">

                                    @if($product->is_featured)
                                        <span class="product-badge">Destacado</span>
                                    @endif

                                    @if(!$isAvailable)
                                        <span class="product-badge badge-danger">Agotado</span>
                                    @endif
                                </div>

                                <div class="product-body">
                                    <p class="product-category">
                                        {{ $product->primaryCategory?->name ?? 'Sin categoría' }}
                                    </p>

                                    <h2 class="product-title">
                                        {{ $product->name }}
                                    </h2>

                                    <p class="product-description">
                                        {{ $product->short_description }}
                                    </p>

                                    <div class="product-footer">
                                        <div>
                                            <span class="price-label">
                                                {{ $hasPrice ? 'Precio' : 'Disponibilidad' }}
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
                                                {{ $isAvailable ? 'Disponible' : 'Agotado' }}
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
                                Anterior
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
                                Siguiente
                            </a>
                        @endif
                    </div>
                @endif
            @else
                <div class="section-heading">
                    <h2>No encontramos productos</h2>
                    <p>
                        Intenta con otro término de búsqueda o selecciona otra categoría.
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
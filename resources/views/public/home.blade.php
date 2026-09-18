@extends('public.layouts.app')

@section('title', 'Inicio')

@section('content')
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    10 años de experiencia
                </div>

                <h1>
                    Equipa tu negocio y
                    <span>transforma tu hogar</span>
                </h1>

                <p>
                    Soluciones confiables desde Mexticacán, Jalisco, para todo México.
                    Encuentra congeladores, refrigeradores, muebles y equipamiento
                    para tu negocio o familia.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('public.catalog') }}" class="primary-button">
                        Ver catálogo
                    </a>

                    <a href="https://wa.me/5210000000000"
                       target="_blank"
                       class="secondary-button">
                        Contactar por WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Quiénes somos -->
    <section class="about-section">
        <div class="container about-container">
            <div class="about-text">
                <span class="about-label">
                    Mueblería Liz y Congela
                </span>

                <h2>
                    Soluciones confiables para tu negocio y tu hogar
                </h2>

                <p>
                    Con 10 años de experiencia, en Mueblería Liz y Congela ayudamos
                    a emprendedores y familias a encontrar productos funcionales,
                    duraderos y adecuados para sus necesidades.
                </p>

                <p>
                    Somos orgullosamente parte de “El Pueblo de los Paleteros” y
                    conocemos lo que tu negocio necesita para trabajar mejor,
                    conservar tus productos y crecer.
                </p>

                <p>
                    Desde Mexticacán, Jalisco, atendemos proyectos en todo México
                    con una atención cercana, práctica y orientada a encontrar la
                    solución adecuada para cada cliente.
                </p>

                <a href="{{ route('public.catalog') }}" class="about-button">
                    Conocer nuestros productos
                </a>
            </div>

            <div class="about-panel">
                <div class="about-panel-title">
                    <span class="about-panel-icon">✦</span>

                    <div>
                        <strong>Todo para tus proyectos</strong>
                        <small>Soluciones para diferentes necesidades</small>
                    </div>
                </div>

                <div class="about-item">
                    <span class="about-item-icon">❄</span>

                    <div>
                        <h3>Equipamiento comercial</h3>
                        <p>
                            Congeladores, refrigeradores y productos para paleterías,
                            neverías, abarrotes, carnicerías y otros negocios.
                        </p>
                    </div>
                </div>

                <div class="about-item">
                    <span class="about-item-icon">⌂</span>

                    <div>
                        <h3>Muebles para el hogar</h3>
                        <p>
                            Productos pensados para brindar comodidad, practicidad
                            y estilo en cada espacio.
                        </p>
                    </div>
                </div>

                <div class="about-item">
                    <span class="about-item-icon">✓</span>

                    <div>
                        <h3>Atención personalizada</h3>
                        <p>
                            Te orientamos para encontrar una opción adecuada y
                            solicitar una cotización por WhatsApp.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($featuredProducts->count())
        <section class="section section-soft">
            <div class="container">
                <div class="section-heading">
                    <h2>Productos destacados</h2>
                    <p>
                        Conoce algunos productos que pueden ayudarte a equipar tu proyecto.
                    </p>
                </div>

                <div class="product-grid">
                    @foreach($featuredProducts as $product)
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
                                        <span class="product-badge">
                                            Destacado
                                        </span>
                                    @endif

                                    @if(!$isAvailable)
                                        <span class="product-badge badge-danger">
                                            Agotado
                                        </span>
                                    @endif
                                </div>

                                <div class="product-body">
                                    <p class="product-category">
                                        {{ $product->primaryCategory?->name ?? 'Sin categoría' }}
                                    </p>

                                    <h3 class="product-title">
                                        {{ $product->name }}
                                    </h3>

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
                                                <span class="quote-price">
                                                    Cotizar
                                                </span>
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
            </div>
        </section>
    @endif

    <section class="section section-light">
        <div class="container">
            <div class="trust-grid">
                <div class="trust-card">
                    <div class="trust-icon">✓</div>
                    <h3>Productos confiables</h3>
                    <p>
                        Soluciones funcionales y duraderas para las necesidades de tu negocio y hogar.
                    </p>
                </div>

                <div class="trust-card">
                    <div class="trust-icon">⌖</div>
                    <h3>Desde Mexticacán</h3>
                    <p>
                        Conocemos las necesidades de emprendedores y familias de nuestra región.
                    </p>
                </div>

                <div class="trust-card">
                    <div class="trust-icon">☏</div>
                    <h3>Atención directa</h3>
                    <p>
                        Solicita información y cotiza tus productos directamente por WhatsApp.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>¿Listo para equipar tu proyecto?</h2>

            <p>
                Visítanos o contáctanos hoy. Te ayudaremos a encontrar los productos
                ideales para tu negocio o tu hogar.
            </p>

            <div class="hero-actions">
                <a href="{{ route('public.catalog') }}" class="primary-button">
                    Explorar catálogo
                </a>

                <a href="https://wa.me/5210000000000"
                   target="_blank"
                   class="secondary-button">
                    Cotizar por WhatsApp
                </a>
            </div>
        </div>
    </section>
@endsection
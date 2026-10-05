@extends('public.layouts.app')

@section('title', 'Refrigeración Comercial y Congeladores')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-snowflakes" aria-hidden="true">
            <span class="snowflake sf-1">❄</span>
            <span class="snowflake sf-2">❄</span>
            <span class="snowflake sf-3">❄</span>
            <span class="snowflake sf-4">❄</span>
            <span class="snowflake sf-5">❄</span>
            <span class="snowflake sf-6">❄</span>
            <span class="snowflake sf-7">❄</span>
            <span class="snowflake sf-8">❄</span>
        </div>

        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span>❄</span> 10 años de experiencia
                </div>

                <h1>
                    Soluciones en Frío y
                    <span>Equipamiento Comercial</span>
                </h1>

                <p>
                    Desde Mexticacán, Jalisco, "El Pueblo de los Paleteros". Encuentra congeladores, 
                    refrigeradores comerciales, neverías y mobiliario de alta durabilidad para tu negocio y tu hogar.
                </p>

                <div class="hero-actions">
                    <a href="{{ route('public.catalog') }}" class="primary-button">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 17L9 20M12 17L15 20M12 17V12M12 17V21M12 7L9 4M12 7L15 4M12 7V12M12 7V3M12 12L7.66989 9.50001M12 12L16.3301 14.5M12 12L7.66988 14.4999M12 12L16.3301 9.49995M16.3301 14.5L17.4282 18.5981M16.3301 14.5L20.4282 13.4019M16.3301 14.5L19.7942 16.5M7.66989 9.50001L3.57181 10.5981M7.66989 9.50001L6.57181 5.40193M7.66989 9.50001L4.20578 7.5M16.3301 9.49995L20.4282 10.598M16.3301 9.49995L17.4282 5.40187M16.3301 9.49995L19.7943 7.5M7.66988 14.4999L6.57181 18.598M7.66988 14.4999L3.57181 13.4019M7.66988 14.4999L4.20584 16.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> 
                        </svg> Ver catálogo de productos
                    </a>

                    <a href="https://wa.me/5213781056303"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="whatsapp-button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20">
                            <path fill="#ffffff" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                        </svg> Cotizar por WhatsApp
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
                    ❄ Mueblería Liz y Congela
                </span>

                <h2>
                    Calidad y rendimiento en tecnología de frío para tu proyecto
                </h2>

                <p>
                    Con más de 10 años de trayectoria, en Mueblería Liz y Congela nos especializamos 
                    en brindar equipos de refrigeración comercial, congeladores y mobiliario diseñados 
                    para resistir las exigencias del trabajo diario.
                </p>

                <p>
                    Al ser parte de Mexticacán, Jalisco, conocemos a fondo las necesidades de paleterías, 
                    neverías, abarrotes, carnicerías y comercios que requieren mantener la máxima frescura y congelación.
                </p>

                <p>
                    Atendemos pedidos en todo México con una atención ágil, profesional y 
                    asesoría directa para elegir el equipo perfecto.
                </p>

                <a href="{{ route('public.catalog') }}" class="about-button">
                    <span>Explorar catálogo en frío</span> →
                </a>
            </div>

            <div class="about-panel">
                <div class="about-panel-title">
                    <span class="about-panel-icon">❄</span>

                    <div>
                        <strong>Líderes en Equipamiento</strong>
                        <small>Frío comercial y confort para el hogar</small>
                    </div>
                </div>

                <div class="about-item">
                    <span class="about-item-icon">🧊</span>

                    <div>
                        <h3>Congeladores y Refrigeración</h3>
                        <p>
                            Vitrinas, conservadores horizontales, congeladores para paleterías, 
                            neveras y equipos comerciales de alto desempeño.
                        </p>
                    </div>
                </div>

                <div class="about-item">
                    <span class="about-item-icon">⌂</span>

                    <div>
                        <h3>Muebles para el Hogar</h3>
                        <p>
                            Mobiliario práctico y durable pensado para brindar confort y elegancia 
                            en cada rincón de tu casa.
                        </p>
                    </div>
                </div>

                <div class="about-item">
                    <span class="about-item-icon">⚡</span>

                    <div>
                        <h3>Atención Personalizada en WhatsApp</h3>
                        <p>
                            Te asesoramos con precios, dimensiones y especificaciones para enviarte 
                            tu cotización al instante.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Productos Destacados -->
    @if($featuredProducts->count())
        <section class="section section-soft">
            <div class="container">
                <div class="section-heading">
                    <h2>Productos Destacados ❄</h2>
                    <p>
                        Explora nuestros equipos de congelación, refrigeradores y artículos con disponibilidad inmediata.
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
                                : 'https://via.placeholder.com/600x450/e0f2fe/0369a1?text=Sin+Imagen';
                        @endphp

                        <article class="product-card">
                            <a href="{{ route('public.product.show', $product->slug) }}">
                                <div class="product-image">
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy">

                                    @if($product->is_featured)
                                        <span class="product-badge">
                                            ❄ Destacado
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
                                        {{ $product->primaryCategory?->name ?? 'Refrigeración' }}
                                    </p>

                                    <h3 class="product-title">
                                        {{ $product->name }}
                                    </h3>

                                    <p class="product-description">
                                        {{ $product->short_description ?: 'Equipo ideal para conservación y congelación de alta calidad.' }}
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
                                                <span class="quote-price">
                                                    Cotizar ❄
                                                </span>
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
            </div>
        </section>
    @endif

    <!-- Confianza / Razones -->
    <section class="section section-light">
        <div class="container">
            <div class="trust-grid">
                <div class="trust-card">
                    <div class="trust-icon">❄</div>
                    <h3>Equipos de Frío Confiables</h3>
                    <p>
                        Diseñados para brindar un rendimiento térmico constante y proteger el inventario de tu negocio.
                    </p>
                </div>

                <div class="trust-card">
                    <div class="trust-icon">📍</div>
                    <h3>Desde Mexticacán, Jalisco</h3>
                    <p>
                        Cuna de emprendedores paleteros. Entendemos el valor de la durabilidad y eficiencia en refrigeración.
                    </p>
                </div>

                <div class="trust-card">
                    <div class="trust-icon">💬</div>
                    <h3>Cotización Instantánea</h3>
                    <p>
                        Sin procesos complicados. Elige tu variante y solicítala directamente por WhatsApp.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to action -->
    <section class="cta">
        <div class="container">
            <h2>¿Listo para equipar tu negocio con tecnología de frío?</h2>

            <p>
                Contáctanos hoy mismo. Te brindamos asesoría personalizada en congeladores, refrigeradores comerciales y mobiliario.
            </p>

            <div class="hero-actions">
                <a href="{{ route('public.catalog') }}" class="primary-button">
                    <span>❄</span> Explorar todo el catálogo
                </a>

                <a href="https://wa.me/5213781056303"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="whatsapp-button">
                    <span>💬</span> Cotizar por WhatsApp
                </a>
            </div>
        </div>
    </section>
@endsection
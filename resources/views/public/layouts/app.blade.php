<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Catálogo') - Mueblería Liz y Congela
    </title>
    <link
        rel="icon"
        type="image/jpeg"
        href="{{ Storage::disk('public')->url('branding/MYLlogo.png') }}"
    >
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/public.css'])
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a
                href="{{ route('public.home') }}"
                class="brand brand-with-logo"
                aria-label="Mueblería Liz y Congela - Inicio"
            >
                <span class="brand-logo-box">
                    <img class="brand-logo"
                        src="{{ Storage::disk('public')->url('branding/MYLlogo.png') }}"
                        alt="Logo de Mueblería Liz"
                        class="brand-logo"
                    >
                </span>

                <span class="brand-text">
                    <strong>Mueblería Liz y Congela</strong>
                    <small>Equipamiento comercial y muebles para el hogar</small>
                </span>
            </a>

            <nav class="main-nav">
                <a href="{{ route('public.home') }}"
                   class="{{ request()->routeIs('public.home') ? 'active' : '' }}">
                    Inicio
                </a>

                <a href="{{ route('public.catalog') }}"
                   class="{{ request()->routeIs('public.catalog') || request()->routeIs('public.product.show') ? 'active' : '' }}">
                    Catálogo
                </a>

                <a href="#contacto">
                    Contacto
                </a>
            </nav>

            <a href="https://wa.me/5210000000000"
               target="_blank"
               class="whatsapp-button">
                <span>◉</span>
                <span>WhatsApp</span>
            </a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer id="contacto" class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a
                        href="{{ route('public.home') }}"
                        class="brand brand-with-logo"
                        aria-label="Mueblería Liz y Congela - Inicio"
                    >
                        <span class="brand-logo-box">
                            <img class="brand-logo"
                                src="{{ Storage::disk('public')->url('branding/MYLlogo.png') }}"
                                alt="Logo de Mueblería Liz"
                                class="brand-logo"
                            >
                        </span>

                        <span class="brand-text">
                            <strong>Mueblería Liz y Congela</strong>
                            <small>Equipamiento comercial y muebles para el hogar</small>
                        </span>
                    </a>

                    <p>
                        Con 10 años de experiencia, ayudamos a emprendedores y familias
                        a encontrar productos funcionales, duraderos y adecuados para sus necesidades.
                    </p>

                    <p>
                        Somos orgullosamente parte de “El Pueblo de los Paleteros”.
                    </p>

                    <p>
                        <strong>
                            Todo para tu negocio. Todo para tu hogar. Todo en un solo lugar.
                        </strong>
                    </p>
                </div>

                <div>
                    <h3>Contacto</h3>

                    <ul>
                        <li>+52 1 000 000 0000</li>
                        <li>contacto@lizzycongela.com</li>
                        <li>Mexticacán, Jalisco, México</li>
                    </ul>
                </div>

                <div>
                    <h3>Horario</h3>

                    <ul>
                        <li>Lunes a viernes: 9:00 AM - 6:00 PM</li>
                        <li>Sábado: 9:00 AM - 2:00 PM</li>
                        <li>Domingo: Cerrado</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <span>
                    © {{ date('Y') }} Mueblería Liz y Congela.
                </span>

                <span>
                    Equipa tu negocio y transforma tu hogar.
                </span>
            </div>
        </div>
    </footer>

    @stack('scripts')
    
</body>
</html>
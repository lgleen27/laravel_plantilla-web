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
                    10 años de experiencia en el mercado
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
                    Mueblería Liz y Congela
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
                    <span>Explorar catálogo en frío</span> <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 12H20M20 12L16 8M20 12L16 16" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

            <div class="about-panel">
                <div class="about-panel-title">
                    <span class="about-panel-icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 17L9 20M12 17L15 20M12 17V12M12 17V21M12 7L9 4M12 7L15 4M12 7V12M12 7V3M12 12L7.66989 9.50001M12 12L16.3301 14.5M12 12L7.66988 14.4999M12 12L16.3301 9.49995M16.3301 14.5L17.4282 18.5981M16.3301 14.5L20.4282 13.4019M16.3301 14.5L19.7942 16.5M7.66989 9.50001L3.57181 10.5981M7.66989 9.50001L6.57181 5.40193M7.66989 9.50001L4.20578 7.5M16.3301 9.49995L20.4282 10.598M16.3301 9.49995L17.4282 5.40187M16.3301 9.49995L19.7943 7.5M7.66988 14.4999L6.57181 18.598M7.66988 14.4999L3.57181 13.4019M7.66988 14.4999L4.20584 16.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> 
                        </svg></span>

                    <div>
                        <strong>Líderes en Equipamiento</strong>
                        <small>Frío comercial y confort para el hogar</small>
                    </div>
                </div>

                <div class="about-item">
                    <span class="about-item-icon"><svg width="30" height="30" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--twemoji" preserveAspectRatio="xMidYMid meet"><path fill="#F5F8FA" d="M4.66 7.479c1.928.786 5.193 1.693 8.25 2.556c2.76.78 4.727 1.8 4.727 4.279c0 1.126-.136 4.519-.185 8.533c.41-.104.791-.153 1.196-.117l-.001-.134c-.038-4.321-.179-8.044-.178-9.239c.003-2.529 1.817-2.208 4.667-2.996c1.297-.359 7.232-1.686 9.209-2.103c1.658-.35 1.012-1.059.494-1.617c-.034.107-.147.202-.383.273c-1.51.453-10.716 2.236-12.941 2.859c-1.569.439-3.591-.367-6.007-1.349c-2.416-.981-8.53-2.416-9.738-2.869a1.547 1.547 0 0 1-.57-.362c-.094.052-.19.103-.278.161c.066-.003.12-.011.19-.012c.477-.008-.195 1.427 1.548 2.137z"></path><path fill="#ADD3E0" d="M1.009 10.872c.096 2.553.356 9.03.119 11.941c-.157 1.923.24 3.361.996 4.403c.204-.333.64-.608 1.36-.736c3.752-.669 9.878-2.385 12.344-3.136c.654-.199 1.16-.378 1.625-.496c.049-4.014.185-7.407.185-8.533c0-2.479-1.967-3.499-4.727-4.279c-3.058-.864-6.323-1.771-8.251-2.557c-1.743-.71-1.071-2.145-1.548-2.138c-.069.001-.123.01-.19.012C1.293 6.432.919 8.508 1.009 10.872z"></path><path fill="#C1E1EA" d="M33.208 27.8c.425.097.866.309 1.268.583c.438-.907.341-2.082.275-3.431c-.119-2.436.059-10.099.238-13.604c.148-2.909-.822-4.267-2.167-4.907a.254.254 0 0 1 .019.2c.517.558 1.163 1.267-.494 1.617c-1.978.417-7.912 1.745-9.209 2.103c-2.85.788-4.664.467-4.667 2.996c-.001 1.195.14 4.919.178 9.239l.001.134c.47.042.98.194 1.638.526c1.367.691 10.883 4.079 12.92 4.544z"></path><path fill="#D2ECF3" d="M13.509 8.424c2.416.981 4.437 1.788 6.007 1.349c2.225-.622 11.431-2.406 12.941-2.859c.237-.071.35-.166.383-.273a4.563 4.563 0 0 1-.286-.327a4.983 4.983 0 0 0-.595-.194c-2.554-.654-8.436-2.495-10.931-3.386c-1.977-.706-4.487-.591-6.594-.119c-2.34.524-7.081 1.706-9.446 2.02c-.71.094-1.296.289-1.788.559c.138.139.32.268.57.362c1.209.452 7.323 1.886 9.739 2.868z"></path><path opacity=".5" fill="#CFE1EA" d="M18.648 22.73a3.517 3.517 0 0 0-1.196.117l-.006.493c-.048 4.716.194 8.644.127 9.281c-.046.438-.315.814-.717 1.072c.821.1 1.641.088 2.424-.042c-.451-.385-.71-.998-.771-1.608c-.064-.655.176-4.556.139-9.313z"></path><path fill="#9BC2D4" d="M17.573 32.621c.067-.637-.176-4.564-.127-9.281l.006-.493c-.465.117-.971.297-1.625.496c-2.466.751-8.592 2.467-12.344 3.136c-.719.128-1.156.404-1.36.736a4.834 4.834 0 0 0 2.152 1.657c2.079.832 6.772 2.495 9.743 3.98c.9.45 1.868.721 2.838.84c.403-.257.671-.633.717-1.071zm14.505-2.62c1.347-.331 2.046-.888 2.398-1.618c-.402-.274-.843-.486-1.268-.583c-2.037-.465-11.554-3.853-12.922-4.544c-.658-.332-1.168-.485-1.638-.526c.037 4.758-.203 8.658-.138 9.313c.061.611.32 1.223.771 1.608a7.529 7.529 0 0 0 1.451-.382c2.019-.773 8.197-2.496 11.346-3.268z"></path><path fill="#C1E1EA" d="M7.571 18.69c-3.911 3.322-5.322 9.72-5.206 6.824c.096-2.391-.017-9.487-.006-12.439c.009-2.491.416-4.634 3.725-3.095c2.538 1.18 7.2 2.114 9.384 3.59c1.411.954-2.464.505-7.897 5.12z"></path><path fill="#D2ECF3" d="M30.169 15.439c2.65 1.793 3.569 5.587 3.497 3.781c-.06-1.491.006-4.939 0-6.779c-.006-1.553.19-2.874-1.997-2.245c-1.88.54-5.124.847-6.486 1.767c-.88.594 1.408 1.056 4.986 3.476z"></path></svg></span>

                    <div>
                        <h3>Congeladores y Refrigeración</h3>
                        <p>
                            Vitrinas, conservadores horizontales, congeladores para paleterías, 
                            neveras y equipos comerciales de alto desempeño.
                        </p>
                    </div>
                </div>

                <div class="about-item">
                    <span class="about-item-icon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 500 500" xml:space="preserve" width="30" height="30" fill="#a3a3a3" stroke="#a3a3a3" stroke-width="1">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="4"/>
                    <g id="SVGRepo_iconCarrier"> <g transform="translate(0 -540.36)"> <path style="fill:#FFFFFF;" d="M250,540.36c-138.1,0-250,111.9-250,250s111.9,250,250,250s250-111.9,250-250 S388.1,540.36,250,540.36L250,540.36z"/> <path style="fill:#86D0EF;" d="M248.8,550.16c-45.6,0.2-90.1,13.4-128.4,38c-80.8,51.8-122.9,146.8-107.1,241.4l0.6-0.1 c2.1,13.3,5.4,26.5,9.8,39.3h453c13.1-39.3,16.6-82.2,9.1-122.3h-0.3c-11.6-63.1-48-118.9-101.1-155 C344.4,564.36,297.1,549.96,248.8,550.16z"/> <path style="fill:#F3F3F3;" d="M375.8,840.36c-22.4,0-40.5,10.7-40.5,24c0,1.5,0.3,3,0.7,4.4H146.3c-3.9-11.1-20.5-19-39.7-19 c-10.2,0-20,2.3-27.5,6.4c-2.5-0.3-5-0.4-7.6-0.4c-15.2,0-29.2,5.1-36.1,13.1H23.7c33.3,96.7,124.2,161.6,226.5,161.7 c102.2-0.1,193.2-65.1,226.5-161.7h-12.3c-4.7-10.5-20.8-17.9-39.2-17.9c-4.9,0-9.7,0.5-14.2,1.6 C403.7,844.96,390.3,840.36,375.8,840.36z"/> <g> <circle style="fill:#FFFFFF;" cx="405.3" cy="672.96" r="4.4"/> <circle style="fill:#FFFFFF;" cx="308" cy="624.36" r="4.4"/> <circle style="fill:#FFFFFF;" cx="374.5" cy="615.86" r="4.4"/> <circle style="fill:#FFFFFF;" cx="217.3" cy="646.46" r="4.4"/> <circle style="fill:#FFFFFF;" cx="203.5" cy="711.06" r="4.4"/> <circle style="fill:#FFFFFF;" cx="125.1" cy="723.76" r="4.4"/> <circle style="fill:#FFFFFF;" cx="44.8" cy="710.56" r="4.4"/> <circle style="fill:#FFFFFF;" cx="119" cy="627.86" r="4.4"/> <circle style="fill:#FFFFFF;" cx="364.5" cy="716.06" r="4.4"/> <circle style="fill:#FFFFFF;" cx="453.5" cy="741.96" r="4.4"/> <circle style="fill:#FFFFFF;" cx="396.8" cy="779.46" r="4.4"/> <circle style="fill:#FFFFFF;" cx="476.1" cy="803.86" r="4.4"/> <circle style="fill:#FFFFFF;" cx="56.8" cy="833.46" r="4.4"/> <circle style="fill:#FFFFFF;" cx="99.7" cy="781.26" r="4.4"/> <circle style="fill:#FFFFFF;" cx="28.9" cy="757.66" r="4.4"/> <circle style="fill:#FFFFFF;" cx="85.6" cy="674.76" r="4.4"/> <circle style="fill:#FFFFFF;" cx="158.8" cy="668.66" r="4.4"/> <circle style="fill:#FFFFFF;" cx="197.2" cy="580.66" r="4.4"/> <circle style="fill:#FFFFFF;" cx="269.5" cy="582.36" r="4.4"/> <path style="fill:#FFFFFF;" d="M319.9,560.66c-2,0.5-3.4,2.3-3.4,4.3c0,2.4,2,4.4,4.4,4.4s4.4-2,4.4-4.4c0-0.9-0.3-1.9-0.9-2.6 C323,561.76,321.4,561.16,319.9,560.66z"/> <path style="fill:#FFFFFF;" d="M151.3,571.56c-2.5,1.2-4.9,2.4-7.4,3.6c0.1,1.6,1,2.9,2.4,3.6c2.2,1.1,4.8,0.2,5.9-2l0,0 C153.1,575.06,152.7,572.96,151.3,571.56z"/> <circle style="fill:#FFFFFF;" cx="306.6" cy="687.46" r="4.4"/> <circle style="fill:#FFFFFF;" cx="245.1" cy="776.76" r="4.4"/> </g> <path style="fill:#E9E9E9;" d="M253.6,720.36c1.8,0,3.2,1.5,3.2,3.3v37.1c0,1.8-1.4,3.3-3.2,3.3s-3.2-1.5-3.2-3.3v-37.1 C250.4,721.76,251.8,720.36,253.6,720.36z"/> <path style="fill:#ff1900;" d="M258.6,721.16v14.4l22.2-6.1L258.6,721.16z"/> <g> <path style="fill:#FFFFFF;" d="M254,742.96c-20.3,0.7-40,7-57.1,18c-37.6,24.4-58.1,69.1-52.7,115.1h1.7c0.1,1.4,0.2,2.8,0.3,4.2 l228.6,1.2c0.1-1.4,0.1-2.8,0.2-4.3h1.7c4.2-46-17.4-90.9-55.7-115.7C300.8,748.56,277.5,742.06,254,742.96z"/> <path style="fill:#FFFFFF;" d="M145.3,880.16c25.5,11,71.9,18.1,120.1,18.5c48.2,0.3,90.3-6.1,109-16.8"/> <rect x="172.7" y="877.06" style="fill:#FFFFFF;" width="201.9" height="5"/> </g> <g> <path style="fill:#E9E9E9;" d="M179.9,812.36l-29.7,2.5l5.8,0.8l6.4,2.3l1.3,0.9c-1.7,1.4-3.4,3-5,4.9 c-12.9,15.1-19.9,42.8-18.1,71.3l79.8,0.8c1.5-28.5-6-56.3-19.1-71.7c-6.5-7.5-13.9-11.5-21.5-11.5L179.9,812.36L179.9,812.36z"/> <path style="fill:#E9E9E9;" d="M374.2,865.26c-0.3,0-0.5,0.1-0.6,0.1c-18.6,10.6-60.4,17.1-108.2,16.7c-15.4-0.1-30.6-0.9-45-2.3 c0,0.3,0,0.5,0.1,0.8c14.8,1.5,30.3,2.3,46.1,2.4c48.6,0.4,90.9-6.2,109.7-17c0.3-0.2,0-0.4-0.7-0.6 C375.1,865.36,374.6,865.26,374.2,865.26z"/> <path style="fill:#E9E9E9;" d="M372.3,844.96c-0.3,0-0.5,0-0.7,0.1c-18,10.3-58.6,16.5-105,16.2c-17.1-0.1-33.9-1.1-49.5-2.9 c0.1,0.3,0.1,0.5,0.2,0.8c16,1.8,33.1,2.8,50.4,2.9c47.2,0.3,88.2-6,106.5-16.5c0.3-0.2-0.1-0.4-0.8-0.6 C373.1,844.96,372.7,844.96,372.3,844.96z"/> <path style="fill:#E9E9E9;" d="M366.3,824.66c-0.3,0-0.5,0-0.7,0.1c-17.5,9.1-57.1,14.7-102.3,14.4c-18.9-0.1-37.3-1.3-54.2-3.2 c0.1,0.3,0.3,0.5,0.4,0.8c17.2,2,36,3.1,55,3.3c46,0.3,86-5.3,103.8-14.7c0.3-0.2-0.1-0.4-0.8-0.6 C367.1,824.66,366.6,824.66,366.3,824.66z"/> <path style="fill:#E9E9E9;" d="M160.5,802.16c0.6-0.1,1.4,0.1,1.9,0.3c21.3,10.2,60.3,16.9,101.3,17.2 c40.9,0.3,76.7-5.8,92.6-15.8c0.3-0.2,1.1-0.2,1.8,0c0.7,0.2,1,0.5,0.8,0.6c-16.2,10.2-52.4,16.4-94,16.1 c-41.6-0.3-82-7.2-104.3-17.8c-0.9-0.1-1.6-0.3-1.6-0.5c0-0.3,0.8-0.4,1.7-0.2C160.5,802.06,160.5,802.06,160.5,802.16 L160.5,802.16z"/> <path style="fill:#E9E9E9;" d="M171.9,783.86c0.5-0.1,1.3,0.1,1.8,0.3c19,10.7,53.7,17.6,90,18c36.4,0.3,68.2-6.1,82.3-16.5 c0.3-0.2,1-0.2,1.6,0s1,0.5,0.7,0.7l0,0c-14.4,10.7-46.6,17.2-83.7,16.8s-72.9-7.5-92.8-18.6 C171.5,784.26,171.6,784.06,171.9,783.86L171.9,783.86z"/> <path style="fill:#E9E9E9;" d="M191,765.76c0.6-0.1,1.5,0.1,2,0.3c14.3,6,40.5,9.9,68.2,10.1c27.7,0.2,52-3.4,62.7-9.3 c0.3-0.2,1.1-0.2,1.9,0s1.1,0.4,0.9,0.6l0,0c-11.1,6.1-35.8,9.8-64.2,9.6c-28.4-0.2-56.1-4.3-71.5-10.7 C190.4,766.16,190.4,765.86,191,765.76L191,765.76L191,765.76z"/> <path style="fill:#E9E9E9;" d="M340.8,894.16c-0.3-0.5-0.6-1.3-0.6-1.9c-0.2-18.8-9.1-49.9-23.2-80.8 c-14.1-30.9-30.8-55-43.5-64.1c-0.7-0.4-1.2-3.1-0.5-2.6c13,9.3,29.9,33.8,44.3,65.3s23.8,64,24,84 C341.4,894.56,341.1,894.66,340.8,894.16L340.8,894.16z"/> <path style="fill:#E9E9E9;" d="M235.1,745.56c-0.1-0.1-0.3,0-0.5,0.2c-14.3,12.8-31.2,39-45.1,69.1c0.3,0.1,0.5,0.2,0.8,0.3 c13.6-29.8,30.1-55.2,43.7-67.4c0.4-0.4,0,0,1.1-1.7C235.2,745.86,235.2,745.66,235.1,745.56L235.1,745.56z M167.3,895.36 c0,0.2,0,0.5,0.1,0.7c0,0.5,0.6,0,1.1-0.7H167.3L167.3,895.36z"/> <path style="fill:#E9E9E9;" d="M255.2,898.36c-0.1-0.6,0-1.6,0.2-2.1c5.4-16.3,8.1-46,6.9-77.7s-5.6-58.4-12.3-72 c-0.4-0.8,0.1-3.6,0.5-2.8c6.8,13.9,11.3,41.1,12.5,73.5s-1.6,63.7-7.4,81.2C255.6,899.06,255.3,899.06,255.2,898.36z"/> </g> <path style="fill:#FFFFFF;" d="M191.9,895.96c1.5-27.7-6-54.8-19.4-69.7s-30.1-15.1-43.1-0.4s-20.2,41.6-18.3,69.4"/> <path style="fill:#E9E9E9;" d="M147,841.16c-4.7,0.3-9.3,2.8-13.3,7.3c-5.9,6.7-10.1,17.3-11.7,29.6c3.9-0.7,8-1.1,12.2-1.1 c18.8,0,35.1,7.7,39.4,18.5h2c1-18.6-4.1-36.7-13-46.7C158,843.46,152.5,840.86,147,841.16L147,841.16z"/> <ellipse style="fill:#F3F3F3;" cx="137.5" cy="899.06" rx="36.6" ry="22.6"/> </g> </g>
                    </svg></span>

                    <div>
                        <h3>Muebles para el Hogar</h3>
                        <p>
                            Mobiliario práctico y durable pensado para brindar confort y elegancia 
                            en cada rincón de tu casa.
                        </p>
                    </div>
                </div>

                <div class="about-item">
                    <span class="about-item-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="30" height="30">
                            <path fill="#1daa61" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                        </svg></span>

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
                                                    Cotizar
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
                    <span class="trust-icon"><svg viewBox="0 0 24 24" width="40" height="40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 17L9 20M12 17L15 20M12 17V12M12 17V21M12 7L9 4M12 7L15 4M12 7V12M12 7V3M12 12L7.66989 9.50001M12 12L16.3301 14.5M12 12L7.66988 14.4999M12 12L16.3301 9.49995M16.3301 14.5L17.4282 18.5981M16.3301 14.5L20.4282 13.4019M16.3301 14.5L19.7942 16.5M7.66989 9.50001L3.57181 10.5981M7.66989 9.50001L6.57181 5.40193M7.66989 9.50001L4.20578 7.5M16.3301 9.49995L20.4282 10.598M16.3301 9.49995L17.4282 5.40187M16.3301 9.49995L19.7943 7.5M7.66988 14.4999L6.57181 18.598M7.66988 14.4999L3.57181 13.4019M7.66988 14.4999L4.20584 16.5" stroke="#0277b5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> 
                        </svg></span>
                    <h3>Equipos de Frío Confiables</h3>
                    <p>
                        Diseñados para brindar un rendimiento térmico constante y proteger el inventario de tu negocio.
                    </p>
                </div>

                <div class="trust-card">
                    <span class="trust-icon"><svg width="55" height="55" viewBox="0 0 1024 1024" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M309.2 584.776h105.5l-49 153.2H225.8c-7.3 0-13.3-6-13.3-13.3 0-2.6 0.8-5.1 2.2-7.3l83.4-126.7c2.5-3.6 6.7-5.9 11.1-5.9z" fill="#FFFFFF" /><path d="M404.5 791.276H225.8c-36.7 0-66.5-29.8-66.5-66.5 0-13 3.8-25.7 11-36.6l83.4-126.7c12.3-18.7 33.1-29.9 55.5-29.9h178.4l-83.1 259.7z m-95.3-206.5c-4.5 0-8.6 2.2-11.1 6l-83.4 126.7c-1.4 2.2-2.2 4.7-2.2 7.3 0 7.3 6 13.3 13.3 13.3h139.9l49-153.2H309.2z" fill="#333333" /><path d="M454.6 584.776h109.6l25.3 153.3H429.3z" fill="#FFFFFF" /><path d="M652.2 791.276H366.6l42.8-259.6h200l42.8 259.6z m-222.9-53.2h160.2l-25.3-153.3H454.6l-25.3 153.3z" fill="#333333" /><path d="M618.6 584.776h105.5c4.5 0 8.6 2.2 11.1 6l83.5 126.7c4 6.1 2.3 14.4-3.8 18.4-2.2 1.4-4.7 2.2-7.3 2.2H667.7l-49.1-153.3z" fill="#FFFFFF" /><path d="M807.6 791.276H628.9l-83.1-259.7h178.4c22.4 0 43.2 11.2 55.5 29.9l83.4 126.7c9.8 14.8 13.2 32.6 9.6 50s-13.7 32.3-28.6 42.1c-10.8 7.2-23.5 11-36.5 11z m-139.9-53.2h139.9c2.6 0 5.1-0.8 7.3-2.2 4-2.6 5.3-6.4 5.7-8.4 0.4-2 0.7-6-1.9-10l-83.4-126.6c-2.5-3.8-6.6-6-11.1-6H618.6l49.1 153.2z" fill="#333333" /><path d="M534.1 639.7C652.5 537.4 711.7 445.8 711.7 365c0-127-102.7-212.1-195-212.1s-195 85.1-195 212.1c0 80.8 59.2 172.3 177.7 274.7 9.9 8.6 24.7 8.6 34.7 0z" fill="#8CAAFF" /><path d="M516.7 672.7c-12.5 0-24.9-4.3-34.8-12.9C356.2 551.2 295.1 454.7 295.1 365c0-142.8 114.6-238.7 221.6-238.7S738.3 222.2 738.3 365c0 89.7-61.1 186.2-186.9 294.8-9.8 8.6-22.3 12.9-34.7 12.9z m0-493.2c-79.7 0-168.4 76.2-168.4 185.5 0 72.3 56.7 158 168.4 254.6C628.5 523 685.1 437.3 685.1 365c0-109.3-88.7-185.5-168.4-185.5z" fill="#333333" /><path d="M516.7 348m-97.5 0a97.5 97.5 0 1 0 195 0 97.5 97.5 0 1 0-195 0Z" fill="#FFFFFF" /><path d="M516.7 472.1c-68.4 0-124.1-55.7-124.1-124.1s55.7-124.1 124.1-124.1S640.8 279.5 640.8 348 585.1 472.1 516.7 472.1z m0-195.1c-39.1 0-70.9 31.8-70.9 70.9 0 39.1 31.8 70.9 70.9 70.9s70.9-31.8 70.9-70.9c0-39.1-31.8-70.9-70.9-70.9z" fill="#333333" /></svg></span>
                    <h3>Desde Mexticacán, Jalisco</h3>
                    <p>
                        Cuna de emprendedores paleteros. Entendemos el valor de la durabilidad y eficiencia en refrigeración.
                    </p>
                </div>

                <div class="trust-card">
                    <span class="trust-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="40" height="40">
                            <path fill="#1daa61" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                        </svg></span>
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
                    <svg viewBox="0 0 24 24" width="25" height="25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 17L9 20M12 17L15 20M12 17V12M12 17V21M12 7L9 4M12 7L15 4M12 7V12M12 7V3M12 12L7.66989 9.50001M12 12L16.3301 14.5M12 12L7.66988 14.4999M12 12L16.3301 9.49995M16.3301 14.5L17.4282 18.5981M16.3301 14.5L20.4282 13.4019M16.3301 14.5L19.7942 16.5M7.66989 9.50001L3.57181 10.5981M7.66989 9.50001L6.57181 5.40193M7.66989 9.50001L4.20578 7.5M16.3301 9.49995L20.4282 10.598M16.3301 9.49995L17.4282 5.40187M16.3301 9.49995L19.7943 7.5M7.66988 14.4999L6.57181 18.598M7.66988 14.4999L3.57181 13.4019M7.66988 14.4999L4.20584 16.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> 
                        </svg> Explorar todo el catálogo
                </a>

                <a href="https://wa.me/5213781056303"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="whatsapp-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="30" height="30">
                        <path fill="#ffffff" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                    </svg> Cotizar por WhatsApp
                </a>
            </div>
        </div>
    </section>
@endsection
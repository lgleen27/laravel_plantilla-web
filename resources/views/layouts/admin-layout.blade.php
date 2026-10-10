<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') - Mueblería Liz y Congela</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link
        rel="icon"
        type="image/jpeg"
        href="{{ Storage::disk('public')->url('branding/MYLlogo.png') }}"
    >
    
    <!-- Scripts y Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AlpineJS para interactividad móvil -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50" x-data="{ sidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-slate-900/50 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 lg:static lg:translate-x-0 transition-transform duration-300 ease-in-out">
            @include('layouts.partials.admin-sidebar')
        </div>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 w-full overflow-hidden">
            
            <!-- Mobile Header (Solo visible en celulares) -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-slate-200 lg:hidden">
                <div class="text-lg font-bold text-sky-600">LIZ Y CONGELA ❄</div>
                <button @click="sidebarOpen = true" class="text-slate-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </header>

            <!-- Page Header (Opcional, se inyecta desde las vistas) -->
            @isset($header)
                <header class="bg-white border-b border-slate-200 hidden lg:block">
                    <div class="px-8 py-6 max-w-7xl mx-auto">
                        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
                            {{ $header }}
                        </h1>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                
                <!-- Alertas de Sesión -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-rose-100 border border-rose-200 text-rose-800 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Contenido dinámico -->
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
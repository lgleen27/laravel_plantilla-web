<!-- Sidebar Container -->
<aside class="flex flex-col w-64 h-screen bg-slate-900 text-white shadow-xl transition-transform duration-300 ease-in-out">
    
    <!-- Logo / Brand -->
    <div class="flex items-center justify-center h-20 border-b border-slate-800">
        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center">
            <span class="text-xl font-bold tracking-wider text-sky-400">LIZ Y CONGELA <span class="text-white">❄</span></span>
            <span class="text-xs text-slate-400 uppercase tracking-widest mt-1">Administración</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>

        <!-- Categorías -->
        <a href="{{ route('admin.categories.index') }}" 
           class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
            Categorías
        </a>

        <!-- Productos -->
        <a href="{{ route('admin.products.index') }}" 
           class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            Productos
        </a>

        <!-- Usuarios (Solo Admin) -->
        @if(auth()->user()->hasRole('super-admin'))
        <a href="{{ route('admin.users.index') }}" 
           class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-sky-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Usuarios & Roles
        </a>
        @endif
        
    </nav>

    <!-- Bottom Actions (User & Logout) -->
    <div class="border-t border-slate-800 p-4">
        <!-- Ver sitio web -->
        <a href="{{ route('public.home') }}" target="_blank" class="flex items-center justify-center w-full px-4 py-2 mb-4 text-sm font-medium text-sky-400 bg-slate-800 rounded-lg hover:bg-slate-700 transition">
            Ver Sitio Público <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
        </a>

        <!-- User Info -->
        <div class="flex items-center mb-4 px-2">
            <div class="w-8 h-8 rounded-full bg-sky-500 flex items-center justify-center text-white font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->getRoleNames()->first() ?? 'Admin' }}</p>
            </div>
        </div>

        <!-- Logout Form -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Cerrar Sesión
            </button>
        </form>
    </div>
</aside>
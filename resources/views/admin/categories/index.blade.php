@extends('layouts.admin-layout')

@section('title', 'Gestión de Categorías')
@section('header', 'Categorías del Catálogo')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false, modalTitle: '', modalProducts: [] }">

    <!-- Recuadro Superior: Crear y Buscar -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row gap-6 justify-between items-start md:items-end">
        
        <!-- Formulario de Creación Rápida -->
        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex-1 flex flex-col sm:flex-row gap-4 items-end w-full">
            @csrf
            <div class="w-full sm:w-64">
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nueva Categoría</label>
                <input type="text" name="name" id="name" required placeholder="Ej. Congeladores Horizontales" 
                       class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
            </div>
            <div class="flex items-center h-10 mb-1">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="ml-2 text-sm text-slate-600">Activa</span>
                </label>
            </div>
            <button type="submit" class="h-10 px-4 bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg shadow-sm transition">
                + Agregar
            </button>
        </form>

        <!-- Barra de Búsqueda -->
        <form action="{{ route('admin.categories.index') }}" method="GET" class="w-full md:w-auto flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar categoría..." 
                   class="w-full md:w-64 rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
            <button type="submit" class="px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded-lg transition">
                Buscar
            </button>
            @if(request('search'))
                <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 text-slate-500 hover:text-rose-500">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Lista de Categorías -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-sm font-semibold text-slate-600">
                    <th class="p-4">Categoría</th>
                    <th class="p-4 text-center">Estado</th>
                    <th class="p-4 text-center">Métricas de Productos</th>
                    <th class="p-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $category)
                    <tr class="hover:bg-slate-50/50 transition" x-data="{ editing: false }">
                        
                        <!-- Columna: Nombre (Vista normal y Edición) -->
                        <td class="p-4 align-middle">
                            <div x-show="!editing" class="font-medium text-slate-800">{{ $category->name }}</div>
                            
                            <!-- Formulario Inline de Edición -->
                            <form x-show="editing" x-cloak action="{{ route('admin.categories.update', $category) }}" method="POST" class="flex gap-2 items-center" @submit="editing = false">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $category->name }}" required class="w-full text-sm rounded border-slate-300 focus:ring-sky-500 h-8">
                                <label class="flex items-center text-xs text-slate-500 gap-1 whitespace-nowrap">
                                    <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600"> Activa
                                </label>
                                <button type="submit" class="p-1.5 bg-emerald-100 text-emerald-700 rounded hover:bg-emerald-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></button>
                                <button type="button" @click="editing = false" class="p-1.5 bg-slate-100 text-slate-600 rounded hover:bg-slate-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </form>
                        </td>

                        <!-- Columna: Estado -->
                        <td class="p-4 text-center align-middle">
                            <span x-show="!editing" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>

                        <!-- Columna: Métricas -->
                        <td class="p-4 align-middle">
                            <div class="flex items-center justify-center gap-3">
                                <span class="text-xs font-medium text-slate-600 bg-slate-100 px-2 py-1 rounded" title="Total Productos">
                                    📦 {{ $category->total_products }}
                                </span>
                                <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-1 rounded" title="Disponibles">
                                    ✅ {{ $category->available_products }}
                                </span>
                                <span class="text-xs font-medium text-rose-700 bg-rose-50 px-2 py-1 rounded" title="Agotados">
                                    ✕ {{ $category->out_of_stock_products }}
                                </span>
                                
                                <!-- Botón Lupa (Modal) -->
                                <button type="button" @click="modalTitle = '{{ $category->name }}'; modalProducts = {{ $category->products->toJson() }}; modalOpen = true" 
                                        class="p-1.5 text-sky-600 hover:bg-sky-50 rounded transition" title="Ver detalles rápidos">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </div>
                        </td>

                        <!-- Columna: Acciones -->
                        <td class="p-4 text-right align-middle">
                            <div class="flex items-center justify-end gap-2" x-show="!editing">
                                <button type="button" @click="editing = true" class="text-sky-600 hover:text-sky-800 text-sm font-medium">Editar</button>
                                
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-sm font-medium">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-500">No hay categorías registradas o que coincidan con la búsqueda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        @if($categories->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- Modal de Lupa (Inspección rápida) -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="modalOpen = false"></div>
        
        <div x-show="modalOpen" x-transition class="relative bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800" x-text="'Resumen: ' + modalTitle"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-4 max-h-64 overflow-y-auto">
                <p x-show="modalProducts.length === 0" class="text-sm text-slate-500 text-center py-4">No hay productos en esta categoría.</p>
                <ul class="divide-y divide-slate-100">
                    <template x-for="prod in modalProducts" :key="prod.id">
                        <li class="py-2 flex justify-between items-center text-sm">
                            <span class="text-slate-700 truncate pr-4" x-text="prod.name"></span>
                            <span :class="prod.status === 'active' ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50'" class="px-2 py-0.5 rounded text-xs whitespace-nowrap" x-text="prod.status === 'active' ? 'Disponible' : 'Agotado'"></span>
                        </li>
                    </template>
                </ul>
                <p x-show="modalProducts.length === 10" class="text-xs text-center text-slate-400 mt-4 italic">Mostrando hasta los primeros 10 productos.</p>
            </div>
        </div>
    </div>

</div>
@endsection
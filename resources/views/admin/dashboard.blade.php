@extends('layouts.admin-layout')

@section('title', 'Dashboard')
@section('header', 'Panel Administrativo')

@section('content')
<div class="space-y-6">

    <!-- Fila superior: Mensaje de Bienvenida y Atajos -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <div>
            <h2 class="text-xl font-bold text-slate-800">¡Hola, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-sm text-slate-500 mt-1">Aquí tienes un resumen de cómo va el catálogo hoy.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium rounded-lg shadow-sm transition">
                + Nuevo Producto
            </a>
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition">
                Categorías
            </a>
        </div>
    </div>

    <!-- Tarjetas de Resumen (KPIs) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Tarjeta 1: Total de Productos -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center gap-4 hover:border-sky-300 transition">
            <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total de Productos</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_products'] }}</h3>
            </div>
        </div>

        <!-- Tarjeta 2: Productos Activos -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center gap-4 hover:border-emerald-300 transition">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Disponibles / Activos</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['active_products'] }}</h3>
            </div>
        </div>

        <!-- Tarjeta 3: Categorías -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center gap-4 hover:border-indigo-300 transition">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Categorías Activas</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_categories'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Actividad Reciente -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-800">Agregados recientemente</h3>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-sky-600 hover:text-sky-800 font-medium">Ver todos &rarr;</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentProducts as $product)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500">SKU: {{ $product->sku ?? 'Sin SKU' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ $product->status === 'active' ? 'Disponible' : 'Agotado/Borrador' }}
                        </span>
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-sm text-slate-400 hover:text-sky-600 bg-slate-50 p-2 rounded-lg">Editar</a>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-500 text-sm">
                    Aún no hay productos registrados. <a href="{{ route('admin.products.create') }}" class="text-sky-600 hover:underline">Agrega el primero.</a>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
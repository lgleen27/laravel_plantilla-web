@extends('layouts.admin-layout')

@section('title', 'Gestión de Productos')
@section('header', 'Productos del Catálogo')

@section('content')
<div class="space-y-6" x-data="{ activeAccordion: null }">

    <!-- Recuadro Superior: Crear y Buscar -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row gap-6 justify-between items-start md:items-end">
        <a href="{{ route('admin.products.create') }}" class="h-10 px-6 inline-flex items-center justify-center bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg shadow-sm transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Producto
        </a>

        <!-- Barra de Búsqueda -->
        <form action="{{ route('admin.products.index') }}" method="GET" class="w-full md:w-auto flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar producto o SKU..." 
                   class="w-full md:w-72 rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
            <button type="submit" class="px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded-lg transition">
                Buscar
            </button>
            @if(request('search'))
                <a href="{{ route('admin.products.index') }}" class="px-3 py-2 text-slate-500 hover:text-rose-500">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Lista de Productos por Categoría (Acordeones) -->
    <div class="space-y-4">
                <!-- PRODUCTOS DESTACADOS -->
        @if($featuredProducts->count() > 0 || request('search'))
        <div class="bg-white rounded-xl shadow-sm border border-sky-200 overflow-hidden mb-8">
            <button @click="activeAccordion = activeAccordion === 'featured' ? null : 'featured'" class="w-full px-6 py-4 flex items-center justify-between bg-sky-50 hover:bg-sky-100 transition">
                <div class="flex items-center gap-4">
                    <h3 class="text-lg font-bold text-sky-800">❄ Productos Destacados</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-200 text-sky-800">{{ $featuredProducts->count() }} productos</span>
                </div>
                <svg class="w-5 h-5 text-sky-600 transition-transform duration-200" :class="activeAccordion === 'featured' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeAccordion === 'featured'" x-collapse x-cloak>
                @if($featuredProducts->count() > 0)
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-y border-sky-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="p-3 w-12 text-center">≡</th>
                                <th class="p-3">Producto</th>
                                <th class="p-3 text-slate-400">SKU</th>
                                <th class="p-3 text-slate-400">Precio</th>
                                <th class="p-3 text-center">Estado</th>
                                <th class="p-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <!-- IMPORTANTE: El data-type indica que estamos arrastrando destacados -->
                        <tbody class="sortable-list divide-y divide-slate-100" data-type="featured">
                            @foreach($featuredProducts as $product)
                                @php
                                    $firstVariant = $product->variants->first();
                                    $price = $firstVariant?->price ?? $product->price;
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition bg-white" data-id="{{ $product->id }}">
                                    <td class="p-3 text-center align-middle text-slate-300 cursor-move hover:text-sky-600 drag-handle" title="Arrastra para reordenar destacados">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                    </td>
                                    <td class="p-3 align-middle font-medium text-slate-800">{{ $product->name }}</td>
                                    <td class="p-3 align-middle text-sm text-slate-600">{{ $product->sku ?? '--' }}</td>
                                    <td class="p-3 align-middle text-sm text-slate-600 font-medium">
                                        @if($price > 0) ${{ number_format($price, 2) }} @else <span class="text-slate-400 font-normal">Cotizar</span> @endif
                                    </td>
                                    <td class="p-3 text-center align-middle">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $product->status === 'active' ? 'Disponible' : 'Agotado' }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right align-middle">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('public.product.show', $product->slug) }}" target="_blank" class="text-slate-400 hover:text-sky-600" title="Ver en tienda">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium">Editar</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-center text-slate-500 text-sm">No hay productos destacados.</div>
                @endif
            </div>
        </div>
        @endif
        @foreach($categories as $category)
            <!-- Solo mostramos el acordeón si tiene productos o si estamos buscando -->
            @if($category->products->count() > 0 || request('search'))
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                
                <!-- Botón Encabezado del Acordeón -->
                <button @click="activeAccordion = activeAccordion === {{ $category->id }} ? null : {{ $category->id }}" 
                        class="w-full px-6 py-4 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition">
                    <div class="flex items-center gap-4">
                        <h3 class="text-lg font-bold text-slate-800">{{ $category->name }}</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-700">
                            {{ $category->products->count() }} productos
                        </span>
                    </div>
                    <svg class="w-5 h-5 text-slate-500 transition-transform duration-200" :class="activeAccordion === {{ $category->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <!-- Contenido (La tabla de productos) -->
                <div x-show="activeAccordion === {{ $category->id }}" x-collapse x-cloak>
                    @if($category->products->count() > 0)
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white border-y border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    <th class="p-3 w-12 text-center">≡</th>
                                    <th class="p-3">Producto</th>
                                    <th class="p-3 text-slate-400">SKU</th>
                                    <th class="p-3 text-slate-400">Precio</th>
                                    <th class="p-3 text-center">Estado</th>
                                    <th class="p-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="sortable-list divide-y divide-slate-100" data-category="{{ $category->id }}">
                                @foreach($category->products as $product)
                                    @php
                                        // Obtener el precio de la primera variante o del producto directamente
                                        $firstVariant = $product->variants->first();
                                        $price = $firstVariant?->price ?? $product->price;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition bg-white" data-id="{{ $product->id }}">
                                        
                                        <!-- Botón para Arrastrar -->
                                        <td class="p-3 text-center align-middle text-slate-300 cursor-move hover:text-sky-600 drag-handle" title="Arrastra para reordenar">
                                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                        </td>
                                        
                                        <!-- Producto Info -->
                                        <td class="p-3 align-middle font-medium text-slate-800">
                                            {{ $product->name }}
                                        </td>

                                        <!-- SKU -->
                                        <td class="p-3 align-middle text-sm text-slate-600">
                                            {{ $product->sku ?? '--' }}
                                        </td>

                                        <!-- Precio -->
                                        <td class="p-3 align-middle text-sm text-slate-600 font-medium">
                                            @if($price > 0)
                                                ${{ number_format($price, 2) }}
                                            @else
                                                <span class="text-slate-400 font-normal">Cotizar</span>
                                            @endif
                                        </td>

                                        <!-- Estado -->
                                        <td class="p-3 text-center align-middle">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                {{ $product->status === 'active' ? 'Disponible' : 'Agotado' }}
                                            </span>
                                        </td>

                                        <!-- Acciones -->
                                        <td class="p-3 text-right align-middle">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('public.product.show', $product->slug) }}" target="_blank" class="text-slate-400 hover:text-sky-600" title="Ver en tienda">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium">Editar</a>
                                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Eliminar producto?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-sm font-medium">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-6 text-center text-slate-500 text-sm">No hay productos que coincidan con la búsqueda.</div>
                    @endif
                </div>
            </div>
            @endif
        @endforeach

        <!-- Productos Sin Categoría (Alerta preventiva) -->
        @if($uncategorized->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-rose-200 overflow-hidden mt-8">
            <button @click="activeAccordion = 'uncat'" class="w-full px-6 py-4 flex items-center justify-between bg-rose-50 hover:bg-rose-100 transition">
                <div class="flex items-center gap-4">
                    <h3 class="text-lg font-bold text-rose-800">Productos sin categoría (Revisar)</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-200 text-rose-800">{{ $uncategorized->count() }} productos</span>
                </div>
                <svg class="w-5 h-5 text-rose-500 transition-transform duration-200" :class="activeAccordion === 'uncat' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="activeAccordion === 'uncat'" x-collapse x-cloak>
                 <table class="w-full text-left border-collapse">
                    <tbody class="divide-y divide-slate-100">
                        @foreach($uncategorized as $product)
                            <tr class="hover:bg-slate-50/50 transition bg-white">
                                <td class="p-3 w-12"></td>
                                <td class="p-3 font-medium">{{ $product->name }}</td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-sky-600 hover:text-sky-800 font-medium">Editar y Categorizar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                 </table>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<!-- Librería para el Drag & Drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lists = document.querySelectorAll('.sortable-list');
        
        lists.forEach(list => {
            new Sortable(list, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-sky-50',
                onEnd: function (evt) {
                    const order = [];
                    // Detectar si estamos moviendo la categoría normal o los "destacados"
                    const type = evt.to.getAttribute('data-type') || 'normal'; 

                    evt.to.querySelectorAll('tr').forEach((row, index) => {
                        order.push({
                            id: row.dataset.id,
                            position: index + 1
                        });
                    });

                    fetch('{{ route('admin.products.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        // Enviamos el tipo (destacado o normal)
                        body: JSON.stringify({ order: order, type: type }) 
                    }).then(response => response.json())
                      .then(data => {
                          if(data.success) {
                              console.log('Orden guardado ✅');
                          }
                      });
                }
            });
        });
    });
</script>
@endpush
@endsection
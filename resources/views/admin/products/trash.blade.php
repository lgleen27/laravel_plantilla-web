@extends('layouts.admin-layout')

@section('title', 'Papelera de Productos')
@section('header', 'Papelera de Reciclaje')

@section('content')
<div class="space-y-6">

    <!-- Barra Superior: Navegación, Búsqueda y Vaciar -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="h-10 px-4 inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition border border-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Volver a Productos</span>
            </a>

            @if($trashedProducts->total() > 0)
                <form action="{{ route('admin.products.trash.empty') }}" method="POST" onsubmit="return confirm('ATENCIÓN: ¿Deseas vaciar la papelera por completo?\n\nEsta acción eliminará TODOS los productos en la papelera y sus fotografías del servidor de forma PERMANENTE e IRREVERSIBLE.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="h-10 px-4 inline-flex items-center justify-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-medium rounded-lg border border-rose-200 transition">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        <span>Vaciar Papelera</span>
                    </button>
                </form>
            @endif
        </div>

        <!-- Buscador en papelera -->
        <form action="{{ route('admin.products.trash') }}" method="GET" class="w-full md:w-auto flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar en papelera..." 
                   class="w-full md:w-72 rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm text-sm">
            <button type="submit" class="px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 rounded-lg text-sm font-medium transition">
                Buscar
            </button>
            @if(request('search'))
                <a href="{{ route('admin.products.trash') }}" class="px-3 py-2 text-sm text-slate-500 hover:text-rose-500">Limpiar</a>
            @endif
        </form>
    </div>

    <!-- Contenido de la Papelera -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        @if($trashedProducts->count() > 0)
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse min-w-[750px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            <th class="p-4 w-16 text-center whitespace-nowrap">Foto</th>
                            <th class="p-4 whitespace-nowrap">Producto</th>
                            <th class="p-4 whitespace-nowrap">Categoría</th>
                            <th class="p-4 whitespace-nowrap">Eliminado</th>
                            <th class="p-4 text-right whitespace-nowrap">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($trashedProducts as $product)
                            @php
                                $firstVariantWithMedia = $product->variants->first(fn($v) => $v->media->isNotEmpty()) ?? $product->variants->first();
                                $media = $firstVariantWithMedia?->media->first();
                                $imageUrl = $media ? Storage::disk($media->disk)->url($media->path) : null;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition bg-white">
                                <!-- Imagen -->
                                <td class="p-4 text-center align-middle">
                                    <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs text-slate-400 font-bold">Sin foto</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Nombre y SKU -->
                                <td class="p-4 align-middle">
                                    <div class="font-semibold text-slate-800">{{ $product->name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">SKU: {{ $product->sku ?? 'No asignado' }}</div>
                                </td>

                                <!-- Categoría -->
                                <td class="p-4 align-middle text-sm text-slate-600">
                                    {{ $product->categories->pluck('name')->implode(', ') ?: 'Sin categoría' }}
                                </td>

                                <!-- Fecha de eliminación -->
                                <td class="p-4 align-middle text-sm text-slate-500 whitespace-nowrap">
                                    <div>{{ $product->deleted_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">hace {{ $product->deleted_at->diffForHumans() }}</div>
                                </td>

                                <!-- Acciones: Restaurar o Destruir -->
                                <td class="p-4 text-right align-middle whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Botón Restaurar -->
                                        <form action="{{ route('admin.products.restore', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-semibold transition" title="Restaurar al catálogo activo">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                <span>Restaurar</span>
                                            </button>
                                        </form>

                                        <!-- Botón Eliminar Permanente -->
                                        <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" onsubmit="return confirm('¿ELIMINAR PERMANENTEMENTE?\n\nEl producto \'{{ addslashes($product->name) }}\' se borrará para siempre de la base de datos junto con sus fotografías. Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-semibold transition" title="Borrar definitivamente de la base de datos">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                <span>Destruir</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($trashedProducts->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $trashedProducts->links() }}
                </div>
            @endif
        @else
            <!-- Estado Vacío -->
            <div class="py-16 px-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-700">La papelera está vacía</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    Cuando elimines productos de la lista principal se enviarán aquí antes de ser borrados definitivamente.
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                        Ir al catálogo activo
                    </a>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection

@extends('layouts.admin-layout')

@section('title', 'Gestión de Usuarios')
@section('header', 'Usuarios del Sistema')

@section('content')
<div class="space-y-6" x-data="{ openCreate: {{ $errors->any() ? 'true' : 'false' }} }">

    <!-- Recuadro Superior -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row gap-6 justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Cuentas Administrativas</h3>
            <p class="text-sm text-slate-500">Administra quién tiene acceso al panel y sus permisos.</p>
        </div>
        <button @click="openCreate = !openCreate" class="h-10 px-6 inline-flex items-center justify-center bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg shadow-sm transition">
            <span x-text="openCreate ? 'Cerrar Formulario' : '+ Nuevo Usuario'"></span>
        </button>
    </div>

    <!-- Formulario Desplegable para Crear Usuario -->
    <div x-show="openCreate" style="display: none;" class="transition-all">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 border-l-4 border-l-sky-500">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Registrar Nuevo Usuario</h3>
            <form method="POST" action="{{ route('admin.users.store') }}">
                {{-- Reutilizamos el formulario que ya estilizaste --}}
                @include('admin.users._form', ['submitLabel' => 'Crear usuario'])
            </form>
        </div>
    </div>

    <!-- Lista de Usuarios -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-sm font-semibold text-slate-600">
                    <th class="p-4">Nombre</th>
                    <th class="p-4">Correo</th>
                    <th class="p-4 text-center">Rol</th>
                    <th class="p-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50/50 transition bg-white">
                        <td class="p-4 font-medium text-slate-800">{{ $user->name }}</td>
                        <td class="p-4 text-slate-600">{{ $user->email }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800 capitalize">
                                {{ $user->getRoleNames()->implode(', ') ?: 'Sin rol' }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-sky-600 hover:text-sky-800 text-sm font-medium">Editar</a>
                                @if (! $user->is(auth()->user()) && ! $user->hasRole('super-admin'))
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este usuario?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 text-sm font-medium">Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-500">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
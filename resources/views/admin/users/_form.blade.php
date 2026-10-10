@csrf

{{-- Mensajes de error --}}
@if ($errors->any())
    <div class="mb-6 rounded-lg bg-rose-50 p-4 border border-rose-200 flex items-start">
        <svg class="h-5 w-5 text-rose-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <p class="font-semibold text-rose-800 mb-2">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside text-sm text-rose-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Nombre Completo <span class="text-rose-500">*</span></label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}" required autofocus
                   class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Correo Electrónico <span class="text-rose-500">*</span></label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" required
                   class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
        </div>
    </div>

    <div>
        <label for="role" class="block text-sm font-medium text-slate-700">Rol de Acceso <span class="text-rose-500">*</span></label>
        <select id="role" name="role" required class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm bg-white">
            <option value="">Selecciona un rol</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" @selected(old('role', isset($user) ? $user->getRoleNames()->first() : '') === $role->name)>
                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                </option>
            @endforeach
        </select>
    </div>

    @if (! isset($user))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 pt-6 mt-6">
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Contraseña <span class="text-rose-500">*</span></label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirmar Contraseña <span class="text-rose-500">*</span></label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
            </div>
        </div>
    @endif

    <div class="flex items-center gap-4 pt-6 mt-6 border-t border-slate-100">
        <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg shadow-sm transition-colors">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition">
            Cancelar
        </a>
    </div>
</div>
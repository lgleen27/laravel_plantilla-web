@csrf

<div class="space-y-6">
    <div>
        <x-input-label for="name" value="Nombre" />

        <x-text-input
            id="name"
            name="name"
            type="text"
            class="mt-1 block w-full"
            value="{{ old('name', $user->name ?? '') }}"
            required
            autofocus
        />

        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Correo electrónico" />

        <x-text-input
            id="email"
            name="email"
            type="email"
            class="mt-1 block w-full"
            value="{{ old('email', $user->email ?? '') }}"
            required
        />

        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="role" value="Rol" />

        <select
            id="role"
            name="role"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
            <option value="">Selecciona un rol</option>

            @foreach ($roles as $role)
                <option
                    value="{{ $role->name }}"
                    @selected(old('role', isset($user) ? $user->getRoleNames()->first() : '') === $role->name)
                >
                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                </option>
            @endforeach
        </select>

        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    @if (! isset($user))
        <div>
            <x-input-label for="password" value="Contraseña" />

            <x-text-input
                id="password"
                name="password"
                type="password"
                class="mt-1 block w-full"
                required
                autocomplete="new-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmar contraseña" />

            <x-text-input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                required
                autocomplete="new-password"
            />
        </div>
    @endif

    <div class="flex items-center gap-4">
        <x-primary-button>
            {{ $submitLabel }}
        </x-primary-button>

        <a
            href="{{ route('admin.users.index') }}"
            class="text-sm text-gray-600 underline hover:text-gray-900"
        >
            Cancelar
        </a>
    </div>
</div>
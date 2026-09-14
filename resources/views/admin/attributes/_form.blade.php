@csrf

<div class="space-y-6">
    <div>
        <x-input-label for="name" value="Nombre" />

        <x-text-input
            id="name"
            name="name"
            type="text"
            class="mt-1 block w-full"
            value="{{ old('name', $attribute->name ?? '') }}"
            required
            autofocus
        />

        <p class="mt-1 text-sm text-gray-500">
            Ejemplo: Marca, Material, Capacidad o Compatibilidad.
        </p>

        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="code" value="Código interno (opcional)" />

        <x-text-input
            id="code"
            name="code"
            type="text"
            class="mt-1 block w-full"
            value="{{ old('code', $attribute->code ?? '') }}"
        />

        <p class="mt-1 text-sm text-gray-500">
            Se genera automáticamente a partir del nombre si lo dejas vacío.
        </p>

        <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="type" value="Tipo de campo" />

        <select
            id="type"
            name="type"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
            <option value="">Selecciona un tipo</option>

            @foreach ($types as $value => $label)
                <option
                    value="{{ $value }}"
                    @selected(old('type', $attribute->type ?? '') === $value)
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <p class="mt-1 text-sm text-gray-500">
            Las listas de una o varias opciones permiten configurar valores después de guardar el atributo.
        </p>

        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="sort_order" value="Orden de visualización" />

        <x-text-input
            id="sort_order"
            name="sort_order"
            type="number"
            min="0"
            class="mt-1 block w-full"
            value="{{ old('sort_order', $attribute->sort_order ?? 0) }}"
            required
        />

        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <x-input-label for="is_filterable" value="¿Se podrá usar como filtro?" />

            <select
                id="is_filterable"
                name="is_filterable"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
            >
                <option value="0" @selected(old('is_filterable', isset($attribute) ? (int) $attribute->is_filterable : 0) === 0)>
                    No
                </option>
                <option value="1" @selected(old('is_filterable', isset($attribute) ? (int) $attribute->is_filterable : 0) === 1)>
                    Sí
                </option>
            </select>

            <x-input-error :messages="$errors->get('is_filterable')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="is_required" value="¿Será obligatorio?" />

            <select
                id="is_required"
                name="is_required"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
            >
                <option value="0" @selected(old('is_required', isset($attribute) ? (int) $attribute->is_required : 0) === 0)>
                    No
                </option>
                <option value="1" @selected(old('is_required', isset($attribute) ? (int) $attribute->is_required : 0) === 1)>
                    Sí
                </option>
            </select>

            <x-input-error :messages="$errors->get('is_required')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="is_variant_attribute" value="¿Puede variar por presentación o variante?" />

            <select
                id="is_variant_attribute"
                name="is_variant_attribute"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
            >
                <option value="0" @selected(old('is_variant_attribute', isset($attribute) ? (int) $attribute->is_variant_attribute : 0) === 0)>
                    No
                </option>
                <option value="1" @selected(old('is_variant_attribute', isset($attribute) ? (int) $attribute->is_variant_attribute : 0) === 1)>
                    Sí
                </option>
            </select>

            <x-input-error :messages="$errors->get('is_variant_attribute')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="is_active" value="Estado" />

            <select
                id="is_active"
                name="is_active"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
            >
                <option value="1" @selected(old('is_active', isset($attribute) ? (int) $attribute->is_active : 1) === 1)>
                    Activo
                </option>
                <option value="0" @selected(old('is_active', isset($attribute) ? (int) $attribute->is_active : 1) === 0)>
                    Inactivo
                </option>
            </select>

            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
        </div>
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>
            {{ $submitLabel }}
        </x-primary-button>

        <a
            href="{{ route('admin.attributes.index') }}"
            class="text-sm text-gray-600 underline hover:text-gray-900"
        >
            Cancelar
        </a>
    </div>
</div>
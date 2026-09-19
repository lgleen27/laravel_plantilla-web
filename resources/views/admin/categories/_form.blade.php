@csrf

<div class="space-y-6">
    <div>
        <x-input-label for="parent_id" value="Categoría padre" />

        <select
            id="parent_id"
            name="parent_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
            <option value="">Sin categoría padre (categoría principal)</option>

            @foreach ($parentCategories as $parentCategory)
                <option
                    value="{{ $parentCategory->id }}"
                    @selected(old('parent_id', $category->parent_id ?? '') == $parentCategory->id)
                >
                    {{ $parentCategory->name }}
                </option>
            @endforeach
        </select>

        <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="name" value="Nombre" />

        <x-text-input
            id="name"
            name="name"
            type="text"
            class="mt-1 block w-full"
            value="{{ old('name', $category->name ?? '') }}"
            required
            autofocus
        />

        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" value="Descripción (opcional)" />

        <textarea
            id="description"
            name="description"
            rows="5"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >{{ old('description', $category->description ?? '') }}</textarea>

        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="is_active" value="Estado" />

        <select
            id="is_active"
            name="is_active"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
        >
            <option value="1" @selected(old('is_active', isset($category) ? (int) $category->is_active : 1) === 1)>
                Activa
            </option>

            <option value="0" @selected(old('is_active', isset($category) ? (int) $category->is_active : 1) === 0)>
                Inactiva
            </option>
        </select>

        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>
            {{ $submitLabel }}
        </x-primary-button>

        <a
            href="{{ route('admin.categories.index') }}"
            class="text-sm text-gray-600 underline hover:text-gray-900"
        >
            Cancelar
        </a>
    </div>
</div>
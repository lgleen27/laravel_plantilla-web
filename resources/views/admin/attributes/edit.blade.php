<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar atributo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.attributes.update', $attribute) }}">
                    @csrf
                    @method('PUT')

                    @include('admin.attributes._form', [
                        'submitLabel' => 'Guardar cambios',
                    ])
                </form>
            </div>

            @if ($attribute->requiresOptions())
                <div class="mt-8 bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Opciones de {{ $attribute->name }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-600">
                        Agrega los valores que podrán seleccionarse para este atributo.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('admin.attributes.options.store', $attribute) }}"
                        class="mt-6 grid gap-4 md:grid-cols-2"
                    >
                        @csrf

                        <div>
                            <x-input-label for="label" value="Nombre visible" />
                            <x-text-input id="label" name="label" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('label')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="value" value="Valor interno (opcional)" />
                            <x-text-input id="value" name="value" type="text" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('value')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="hex_code" value="Código de color HEX (opcional)" />
                            <x-text-input id="hex_code" name="hex_code" type="text" class="mt-1 block w-full" placeholder="#000000" />
                            <x-input-error :messages="$errors->get('hex_code')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sort_order" value="Orden" />
                            <x-text-input id="sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full" value="0" required />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        <input type="hidden" name="is_active" value="1">

                        <div class="md:col-span-2">
                            <x-primary-button>
                                Agregar opción
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="mt-8 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Etiqueta</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Valor</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Color</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acción</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @forelse ($attribute->options as $option)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $option->label }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $option->value }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            @if ($option->hex_code)
                                                <span
                                                    class="inline-block h-4 w-4 rounded-full align-middle"
                                                    style="background-color: {{ $option->hex_code }};"
                                                ></span>
                                                <span class="ml-1">{{ $option->hex_code }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-right text-sm">
                                            <form
                                                method="POST"
                                                action="{{ route('admin.attributes.options.destroy', [$attribute, $option]) }}"
                                                class="inline"
                                                onsubmit="return confirm('¿Deseas eliminar esta opción?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="font-medium text-red-600 hover:text-red-900">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                            Aún no existen opciones.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
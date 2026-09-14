<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Atributos configurables
            </h2>

            @can('attributes.create')
                <a
                    href="{{ route('admin.attributes.create') }}"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-black hover:bg-indigo-500"
                >
                    Crear atributo
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-md bg-red-100 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nombre</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Código</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tipo</th>
                                <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Filtro</th>
                                <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Variante</th>
                                <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Opciones</th>
                                <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Estado</th>
                                <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($attributes as $attribute)
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                        {{ $attribute->name }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $attribute->code }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $types[$attribute->type] }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                                        {{ $attribute->is_filterable ? 'Sí' : 'No' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                                        {{ $attribute->is_variant_attribute ? 'Sí' : 'No' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                                        {{ $attribute->options_count }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-sm">
                                        @if ($attribute->is_active)
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Activo
                                            </span>
                                        @else
                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-right text-sm">
                                        @can('attributes.update')
                                            <a
                                                href="{{ route('admin.attributes.edit', $attribute) }}"
                                                class="mr-3 font-medium text-indigo-600 hover:text-indigo-900"
                                            >
                                                Editar
                                            </a>
                                        @endcan

                                        @can('attributes.delete')
                                            <form
                                                action="{{ route('admin.attributes.destroy', $attribute) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('¿Deseas eliminar este atributo?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="font-medium text-red-600 hover:text-red-900">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No hay atributos configurables registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $attributes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
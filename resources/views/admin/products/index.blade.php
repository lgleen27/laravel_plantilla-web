<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Publicaciones
            </h2>

            @can('products.create')
                <a
                    href="{{ route('admin.products.create') }}"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-black hover:bg-indigo-500"
                >
                    Crear publicación
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
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Publicaciones
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    SKU
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Categorías
                                </th>

                                <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Precio
                                </th>

                                <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Estado
                                </th>

                                <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-4 py-4">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $product->name }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $product->slug }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $product->sku ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        @forelse ($product->categories as $category)
                                            <span class="mb-1 inline-block rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">
                                                {{ $category->name }}

                                                @if ($category->pivot->is_primary)
                                                    <span class="font-semibold">(Principal)</span>
                                                @endif
                                            </span>
                                        @empty
                                            —
                                        @endforelse
                                    </td>

                                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                                        @if ($product->price !== null)
                                            ${{ number_format((float) $product->price, 2) }}
                                        @else
                                            Cotizar
                                        @endif
                                    </td>

                                    <td class="px-4 py-4 text-center text-sm">
                                        @if ($product->status === 'active')
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Activo
                                            </span>
                                        @elseif ($product->status === 'inactive')
                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                Inactivo
                                            </span>
                                        @else
                                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                                Borrador
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-right text-sm">
                                        @can('products.update')
                                            <a
                                                href="{{ route('admin.products.edit', $product) }}"
                                                class="mr-3 font-medium text-indigo-600 hover:text-indigo-900"
                                            >
                                                Editar
                                            </a>
                                        @endcan

                                        @can('variants.view')
                                            <a
                                                href="{{ route('admin.products.variants.index', $product) }}"
                                                class="mr-3 font-medium text-purple-600 hover:text-purple-900"
                                            >
                                                Variantes
                                            </a>
                                        @endcan

                                        @can('products.delete')
                                            <form
                                                action="{{ route('admin.products.destroy', $product) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('¿Deseas eliminar esta publicación?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="font-medium text-red-600 hover:text-red-900"
                                                >
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No hay publicaciones registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
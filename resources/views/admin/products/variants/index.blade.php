<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">
                    Producto: {{ $product->name }}
                </p>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Variantes
                </h2>
            </div>

            <div class="flex items-center gap-3">
                @can('variants.create')
                    <a
                        href="{{ route('admin.products.variants.create', $product) }}"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        Crear variante
                    </a>
                @endcan
            </div>
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
                                    Variante
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    SKU
                                </th>

                                <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Precio
                                </th>

                                <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Imágenes
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
                            @forelse ($variants as $variant)
                                <tr>
                                    <td class="px-4 py-4">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $variant->name }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $variant->slug }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $variant->sku ?: '—' }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                                        @if ($variant->price !== null)
                                            ${{ number_format((float) $variant->price, 2) }}
                                        @else
                                            Cotización
                                        @endif
                                    </td>

                                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                                        {{ $variant->media_count }}
                                    </td>

                                    <td class="px-4 py-4 text-center text-sm">
                                        @if ($variant->status === 'active')
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Activa
                                            </span>
                                        @else
                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-right text-sm">
                                        @can('variants.update')
                                            <a
                                                href="{{ route('admin.products.variants.edit', [$product, $variant]) }}"
                                                class="mr-3 font-medium text-indigo-600 hover:text-indigo-900"
                                            >
                                                Editar
                                            </a>
                                        @endcan

                                        @can('variants.view')
                                            <a
                                                href="{{ route('admin.products.variants.media.index', [$product, $variant]) }}"
                                                class="mr-3 font-medium text-blue-600 hover:text-blue-900"
                                            >
                                                Imágenes
                                            </a>
                                        @endcan

                                        @can('variants.delete')
                                            <form
                                                action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('¿Deseas eliminar esta variante?');"
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
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                        Este producto todavía no tiene variantes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $variants->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
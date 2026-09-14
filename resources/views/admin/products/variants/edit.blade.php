<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-gray-500">
                Producto: {{ $product->name }}
            </p>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar variante: {{ $variant->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form
                    method="POST"
                    action="{{ route('admin.products.variants.update', [$product, $variant]) }}"
                >
                    @csrf
                    @method('PUT')

                    @include('admin.products.variants._form', [
                        'submitLabel' => 'Guardar cambios',
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
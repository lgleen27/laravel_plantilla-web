<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-gray-500">
                Producto: {{ $product->name }}
            </p>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear variante
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form
                    method="POST"
                    action="{{ route('admin.products.variants.store', $product) }}"
                    enctype="multipart/form-data"
                    class="p-6 sm:p-8"
                >
                    @include('admin.products.variants._form', [
                        'submitLabel' => 'Crear variante',
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
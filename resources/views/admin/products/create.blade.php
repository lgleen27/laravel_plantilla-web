<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear producto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @include('admin.products._form', [
                        'submitLabel' => 'Crear producto',
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
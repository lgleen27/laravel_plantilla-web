<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear usuario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @include('admin.users._form', [
                        'submitLabel' => 'Crear usuario',
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
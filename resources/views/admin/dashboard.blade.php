<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel administrativo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold">
                        Bienvenido, {{ auth()->user()->name }}
                    </h1>

                    <p class="mt-2 text-gray-600">
                        Selecciona un módulo para administrar la información del catálogo.
                    </p>

                    <div class="mt-4">
                        <p class="text-sm font-semibold text-gray-700">
                            Rol asignado:
                        </p>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ auth()->user()->getRoleNames()->map(fn ($role) => ucfirst(str_replace('-', ' ', $role)))->implode(', ') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @if (auth()->user()->hasRole('super-admin'))
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="rounded-lg bg-white p-6 shadow-sm transition hover:shadow-md"
                    >
                        <h3 class="text-lg font-semibold text-gray-900">
                            Usuarios
                        </h3>

                        <p class="mt-2 text-sm text-gray-600">
                            Crea usuarios, asigna roles y administra los accesos al sistema.
                        </p>
                    </a>
                @endif

                @can('categories.view')
                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="rounded-lg bg-white p-6 shadow-sm transition hover:shadow-md"
                    >
                        <h3 class="text-lg font-semibold text-gray-900">
                            Categorías
                        </h3>

                        <p class="mt-2 text-sm text-gray-600">
                            Crea Categorias Padres e hijos para clasificar los productos.
                        </p>
                    </a>
                @endcan

                @can('products.view')
                    <a
                        href="{{ route('admin.products.index') }}"
                        class="rounded-lg bg-white p-6 shadow-sm transition hover:shadow-md"
                    >
                        <h3 class="text-lg font-semibold text-gray-900">
                            Productos
                        </h3>

                        <p class="mt-2 text-sm text-gray-600">
                            Crea y administra los productos disponibles en el catálogo, con todas sus variantes.
                        </p>
                    </a>
                @endcan

                @can('attributes.view')
                    <a
                        href="{{ route('admin.attributes.index') }}"
                        class="rounded-lg bg-white p-6 shadow-sm transition hover:shadow-md"
                    >
                        <h3 class="text-lg font-semibold text-gray-900">
                            Atributos
                        </h3>

                        <p class="mt-2 text-sm text-gray-600">
                            Aquí se definirán los campos configurables de cada tipo de producto.
                        </p>
                    </a>
                @endcan

                @can('content.view')
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Contenido del sitio
                        </h3>

                        <p class="mt-2 text-sm text-gray-600">
                            Más adelante podrás editar el carrusel, textos, contacto y footer.
                        </p>
                    </div>
                @endcan

                @can('settings.view')
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Configuración
                        </h3>

                        <p class="mt-2 text-sm text-gray-600">
                            Aquí se concentrarán los datos generales de empresa y WhatsApp.
                        </p>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">
                    Producto: {{ $product->name }}
                </p>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Imágenes de: {{ $variant->name }}
                </h2>
            </div>

            <a
                href="{{ route('admin.products.variants.index', $product) }}"
                class="text-sm font-medium text-gray-600 hover:text-gray-900"
            >
                Volver a variantes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-md bg-red-100 p-4 text-red-800">
                    <p class="font-semibold">No se pudieron cargar las imágenes:</p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @can('variants.update')
                <div class="mb-8 bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Cargar imágenes
                    </h3>

                    <p class="mt-1 text-sm text-gray-600">
                        Puedes seleccionar hasta 10 imágenes JPG, PNG o WEBP por carga. Cada archivo puede pesar hasta 5 MB.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('admin.products.variants.media.store', [$product, $variant]) }}"
                        enctype="multipart/form-data"
                        class="mt-5"
                    >
                        @csrf

                        <div>
                            <x-input-label for="images" value="Selecciona las imágenes" />

                            <input
                                id="images"
                                name="images[]"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                multiple
                                class="mt-1 block w-full rounded-md border border-gray-300 p-2 text-sm text-gray-700"
                                required
                            >
                        </div>

                        <div class="mt-4">
                            <x-primary-button>
                                Cargar imágenes
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            @endcan

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Galería
                        </h3>

                        <p class="mt-1 text-sm text-gray-600">
                            La primera imagen cargada se asigna como principal automáticamente.
                        </p>
                    </div>

                    <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">
                        {{ $media->count() }} imágenes
                    </span>
                </div>

                @if ($media->isEmpty())
                    <div class="mt-6 rounded-md border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
                        Esta variante aún no tiene imágenes.
                    </div>
                @else
                    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach ($media as $image)
                            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                                <div class="flex h-40 items-center justify-center overflow-hidden bg-gray-100">
                                    <img
                                        src="{{ Storage::disk($image->disk)->url($image->path) }}"
                                        alt="{{ $image->alt_text ?: $variant->product->name.' - '.$variant->name }}"
                                        class="h-full w-full object-cover"
                                    >
                                </div>

                                <div class="p-4">
                                    <p class="truncate text-sm font-medium text-gray-900">
                                        {{ $image->file_name }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ number_format($image->size / 1024, 1) }} KB
                                    </p>

                                    @if ($image->is_primary)
                                        <span class="mt-3 inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Imagen principal
                                        </span>
                                    @endif

                                    <div class="mt-4 flex flex-wrap gap-3">
                                        @can('variants.update')
                                            @if (! $image->is_primary)
                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.products.variants.media.primary', [$product, $variant, $image]) }}"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-900"
                                                    >
                                                        Marcar como principal
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan

                                        @can('variants.delete')
                                            <form
                                                method="POST"
                                                action="{{ route('admin.products.variants.media.destroy', [$product, $variant, $image]) }}"
                                                onsubmit="return confirm('¿Deseas eliminar esta imagen?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="text-sm font-medium text-red-600 hover:text-red-900"
                                                >
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ordenar Productos Destacados
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="mb-6 text-gray-600">Arrastra y suelta los productos para cambiar el orden en el que aparecerán en la página de inicio pública. Se guarda automáticamente.</p>
                
                <ul id="sortable-list" class="space-y-3">
                    @foreach($products as $product)
                        <li data-id="{{ $product->id }}" class="p-4 bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-between cursor-move hover:bg-gray-100 transition-colors shadow-sm">
                            <div class="flex items-center gap-4">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                <span class="font-medium text-gray-900">{{ $product->name }}</span>
                            </div>
                            <span class="text-sm text-gray-400">ID: {{ $product->id }}</span>
                        </li>
                    @endforeach
                </ul>

                <p id="save-status" class="mt-4 text-sm font-semibold text-emerald-600 hidden transition-opacity">¡Nuevo orden guardado correctamente!</p>
            </div>
        </div>
    </div>

    <!-- Cargar SortableJS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('sortable-list');
            var statusText = document.getElementById('save-status');

            Sortable.create(el, {
                animation: 150,
                onEnd: function () {
                    // Obtener el nuevo orden de IDs
                    let order = [];
                    el.querySelectorAll('li').forEach(function (li) {
                        order.push(li.getAttribute('data-id'));
                    });

                    // Enviar al servidor por detrás (AJAX)
                    fetch("{{ route('admin.products.featured.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order: order })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            statusText.classList.remove('hidden');
                            setTimeout(() => { statusText.classList.add('hidden'); }, 3000);
                        }
                    });
                },
            });
        });
    </script>
</x-app-layout>
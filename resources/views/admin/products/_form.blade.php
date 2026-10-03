@php
    $selectedCategoryId = old(
        'category_id',
        isset($product)
            ? optional($product->categories->firstWhere('pivot.is_primary', true))->id
                ?? $product->categories->first()?->id
            : ''
    );

    // Preparamos las variantes existentes para que Javascript (Alpine) pueda leerlas
    $existingVariants = isset($product) ? $product->variants->map(function($variant) {
        return [
            'id' => $variant->id,
            'name' => $variant->name === 'Única' ? '' : $variant->name,
            'sku' => $variant->sku,
            'media' => $variant->media->map(function($m) {
                return [
                    'id' => $m->id,
                    'url' => Storage::disk($m->disk)->url($m->path)
                ];
            })->values()->toArray()
        ];
    })->values()->toArray() : [];
@endphp

@csrf

{{-- MENSAJES DE ERROR GLOBALES --}}
@if (session('error'))
    <div class="mb-6 rounded-md bg-red-100 p-4 border border-red-200">
        <div class="flex">
            <svg class="h-5 w-5 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="font-medium text-red-800">{!! session('error') !!}</p>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-md bg-red-100 p-4 border border-red-200">
        <div class="flex items-start">
            <svg class="h-5 w-5 text-red-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <p class="font-semibold text-red-800 mb-2">Por favor corrige los siguientes errores:</p>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div class="space-y-8">
    {{-- Información principal --}}
    <section>
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Crear/Editar publicación</h3>
                <p class="mt-1 text-sm text-gray-600">Captura únicamente la información necesaria.</p>
            </div>
        </div>

        <div class="mt-5 gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="name" value="Titulo de la publicación" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $product->name ?? '') }}" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- SKU del Producto --}}
            <div>
                <x-input-label for="sku" value="SKU General (Opcional)" />
                <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full" value="{{ old('sku', $product->sku ?? '') }}" />
                <x-input-error :messages="$errors->get('sku')" class="mt-2" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="category_id" value="Categoría" />
                <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) $selectedCategoryId === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="description" value="Descripción general" />
                <textarea id="description" name="description" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
        </div>
    </section>

    {{-- Imágenes y Variantes Simplificadas --}}
    <section class="border-t border-gray-200 pt-8" x-data='variantManager(@json($existingVariants))'>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Imágenes y Variantes</h3>
                <p class="mt-1 text-sm text-gray-600">Gestiona las fotos y los estilos desde aquí mismo.</p>
            </div>
            <button type="button" @click="addNewVariant()" class="px-4 py-2 bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-md hover:bg-indigo-100 transition-colors">
                + Añadir nueva variante
            </button>
        </div>

        <!-- Inputs ocultos para registrar lo que el usuario decide eliminar -->
        <template x-for="id in deletedVariants"><input type="hidden" name="delete_variants[]" x-bind:value="id"></template>
        <template x-for="id in deletedMedia"><input type="hidden" name="delete_media[]" x-bind:value="id"></template>

        <div class="mt-6 space-y-6">
            
            <!-- Bloques para Variantes EXISTENTES -->
            <template x-for="(variant, index) in existingVariants" :key="'existing_'+variant.id">
                <div class="p-5 border border-indigo-200 rounded-lg bg-indigo-50/30 flex items-start gap-4">
                    <input type="hidden" x-bind:name="'existing_variants[' + variant.id + '][id]'" x-bind:value="variant.id">
                    
                    <div class="flex-1 grid gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label x-bind:for="'existing_variant_name_' + index" value="Variante (Opcional)" />
                                <input type="text" x-bind:id="'existing_variant_name_' + index" x-bind:name="'existing_variants[' + variant.id + '][name]'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white" x-model="variant.name" placeholder="Ej. VERDE" />
                            </div>
                            <div>
                                <x-input-label x-bind:for="'existing_variant_sku_' + index" value="SKU Variante (Opcional)" />
                                <input type="text" x-bind:id="'existing_variant_sku_' + index" x-bind:name="'existing_variants[' + variant.id + '][sku]'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white" x-model="variant.sku" />
                            </div>
                        </div>
                        
                        <div>
                            <x-input-label value="Fotos Actuales (Clic para eliminar)" />
                            <div class="flex flex-wrap gap-3 mt-2">
                                <template x-for="(image, imgIndex) in variant.media" :key="image.id">
                                    <div class="relative group border rounded-md overflow-hidden bg-white shadow-sm cursor-pointer" style="width: 100px; height: 100px; flex-shrink: 0;" @click="deleteMedia(index, imgIndex)">
                                        <img x-bind:src="image.url" style="width: 100px; height: 100px; object-fit: cover;">
                                        <div class="absolute inset-0 bg-red-500 bg-opacity-70 text-white opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity" title="Eliminar foto">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="variant.media.length === 0" class="text-sm text-gray-500 italic mt-2">Te quedaste sin fotos en este grupo.</div>
                            </div>
                        </div>

                        <div>
                            <x-input-label x-bind:for="'existing_variant_images_' + index" value="Añadir MÁS fotos a esta variante (Máx 1MB c/u)" />
                            <input type="file" x-bind:id="'existing_variant_images_' + index" x-bind:name="'existing_variants[' + variant.id + '][new_images][]'" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-gray-100" />
                        </div>
                    </div>
                    
                    <button type="button" @click="deleteExistingVariant(index)" class="mt-7 p-2 text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Eliminar grupo completo">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </template>

            <!-- Bloques para Variantes NUEVAS (Cuando se añade una desde cero) -->
            <template x-for="(variant, index) in newVariants" :key="'new_'+variant.id">
                <div class="p-5 border border-gray-200 rounded-lg bg-gray-50 flex items-start gap-4">
                    <div class="flex-1 grid gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label x-bind:for="'new_variant_name_' + index" value="Variante (Opcional)" />
                                <input type="text" x-bind:id="'new_variant_name_' + index" x-bind:name="'variants[' + index + '][name]'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white" placeholder="Ej. VERDE" />
                            </div>
                            <div>
                                <x-input-label x-bind:for="'new_variant_sku_' + index" value="SKU Variante (Opcional)" />
                                <input type="text" x-bind:id="'new_variant_sku_' + index" x-bind:name="'variants[' + index + '][sku]'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white" />
                            </div>
                        </div>

                        <div>
                            <x-input-label x-bind:for="'new_variant_images_' + index" value="Fotografías (Máx 1MB c/u)" />
                            <input type="file" x-bind:id="'new_variant_images_' + index" x-bind:name="'variants[' + index + '][images][]'" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required />
                        </div>
                    </div>
                    
                    <button x-show="existingVariants.length > 0 || newVariants.length > 1" type="button" @click="removeNewVariant(index)" class="mt-7 p-2 text-red-600 hover:bg-red-50 rounded-md" title="Cancelar adición">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </template>
        </div>
    </section>

    {{-- Estado de Publicación --}}
    <section class="border-t border-gray-200 pt-8">
        <div class="mt-4 grid gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="status" value="Estado" />
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="draft" @selected(old('status', $product->status ?? 'draft') === 'draft')>Borrador</option>
                    <option value="active" @selected(old('status', $product->status ?? 'draft') === 'active')>Activo</option>
                    <option value="inactive" @selected(old('status', $product->status ?? 'draft') === 'inactive')>Inactivo</option>
                </select>
            </div>
            <div>
                <x-input-label for="is_featured" value="¿Mostrar como producto destacado?" />
                <select id="is_featured" name="is_featured" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="0" @selected((int) old('is_featured', $product->is_featured ?? 0) === 0)>No</option>
                    <option value="1" @selected((int) old('is_featured', $product->is_featured ?? 0) === 1)>Sí</option>
                </select>
            </div>
        </div>
    </section>

    {{-- Acciones --}}
    <div class="flex items-center gap-4 border-t border-gray-200 pt-8">
        <x-primary-button>{{ $submitLabel }}</x-primary-button>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 underline hover:text-gray-900">Cancelar</a>
    </div>
</div>

<script>
    function variantManager(existing) {
        return {
            existingVariants: existing,
            deletedVariants: [],
            deletedMedia: [],
            newVariants: [],
            
            init() {
                if (this.existingVariants.length === 0) {
                    this.newVariants.push({ id: Date.now() });
                }
            },
            addNewVariant() {
                this.newVariants.push({ id: Date.now() });
            },
            removeNewVariant(index) {
                this.newVariants.splice(index, 1);
            },
            deleteExistingVariant(index) {
                if(confirm("¿Seguro que deseas eliminar esta variante y todas sus fotos?")) {
                    this.deletedVariants.push(this.existingVariants[index].id);
                    this.existingVariants.splice(index, 1);
                }
            },
            deleteMedia(variantIndex, mediaIndex) {
                if(confirm("¿Seguro que deseas eliminar esta fotografía?")) {
                    this.deletedMedia.push(this.existingVariants[variantIndex].media[mediaIndex].id);
                    this.existingVariants[variantIndex].media.splice(mediaIndex, 1);
                }
            }
        }
    }
</script>
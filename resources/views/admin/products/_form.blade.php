@php
    $selectedCategoryId = old(
        'category_id',
        isset($product)
            ? optional($product->categories->firstWhere('pivot.is_primary', true))->id
                ?? $product->categories->first()?->id
            : ''
    );

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
    <div class="mb-6 rounded-lg bg-rose-50 p-4 border border-rose-200 flex items-center">
        <svg class="h-5 w-5 text-rose-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <p class="font-medium text-rose-800">{!! session('error') !!}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-lg bg-rose-50 p-4 border border-rose-200 flex items-start">
        <svg class="h-5 w-5 text-rose-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <p class="font-semibold text-rose-800 mb-2">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside text-sm text-rose-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="space-y-8">
    {{-- Información principal --}}
    <section>
        <div>
            <h3 class="text-lg font-bold text-slate-800">Información General</h3>
            <p class="mt-1 text-sm text-slate-500">Datos principales para identificar el producto en el catálogo.</p>
        </div>

        <div class="mt-5 grid gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Título de la publicación <span class="text-rose-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $product->name ?? '') }}" required autofocus 
                       class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm" placeholder="Ej. Congelador Horizontal 15 pies">
            </div>

            <div>
                <label for="sku" class="block text-sm font-medium text-slate-700">SKU General (Opcional)</label>
                <input id="sku" name="sku" type="text" value="{{ old('sku', $product->sku ?? '') }}" 
                       class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm" placeholder="Ej. CH-15">
            </div>

            <div class="md:col-span-2">
                <label for="category_id" class="block text-sm font-medium text-slate-700">Categoría <span class="text-rose-500">*</span></label>
                <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm bg-white">
                    <option value="">Selecciona una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) $selectedCategoryId === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-slate-700">Descripción completa</label>
                <textarea id="description" name="description" rows="5" placeholder="Detalla aquí las características del producto..."
                          class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">{{ old('description', $product->description ?? '') }}</textarea>
            </div>
        </div>
    </section>

    {{-- Imágenes y Variantes --}}
    <section class="border-t border-slate-100 pt-8" x-data='variantManager(@json($existingVariants))'>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Variantes y Fotografías</h3>
                <p class="mt-1 text-sm text-slate-500">Agrega fotos para el producto o sepáralo por colores/estilos.</p>
            </div>
            <button type="button" @click="addNewVariant()" class="px-4 py-2 bg-sky-50 text-sky-700 text-sm font-semibold rounded-lg hover:bg-sky-100 border border-sky-200 transition-colors">
                + Añadir nueva variante
            </button>
        </div>

        <!-- Inputs ocultos (borrados) -->
        <template x-for="id in deletedVariants"><input type="hidden" name="delete_variants[]" x-bind:value="id"></template>
        <template x-for="id in deletedMedia"><input type="hidden" name="delete_media[]" x-bind:value="id"></template>

        <div class="mt-6 space-y-6">
            <!-- Variantes EXISTENTES -->
            <template x-for="(variant, index) in existingVariants" :key="'existing_'+variant.id">
                <div class="p-5 border border-slate-200 rounded-xl bg-slate-50 flex items-start gap-4">
                    <input type="hidden" x-bind:name="'existing_variants[' + variant.id + '][id]'" x-bind:value="variant.id">
                    
                    <div class="flex-1 grid gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Nombre de Variante (Opcional)</label>
                                <input type="text" x-bind:name="'existing_variants[' + variant.id + '][name]'" class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 bg-white" x-model="variant.name" placeholder="Ej. Blanco, Acero, etc." />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">SKU de Variante</label>
                                <input type="text" x-bind:name="'existing_variants[' + variant.id + '][sku]'" class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 bg-white" x-model="variant.sku" />
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Fotos Actuales (Clic para eliminar)</label>
                            <div class="flex flex-wrap gap-3 mt-2">
                                <template x-for="(image, imgIndex) in variant.media" :key="image.id">
                                    <div class="relative group border border-slate-200 rounded-lg overflow-hidden bg-white shadow-sm cursor-pointer" style="width: 100px; height: 100px;" @click="deleteMedia(index, imgIndex)">
                                        <img x-bind:src="image.url" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-rose-500/80 text-white opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity" title="Eliminar foto">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="variant.media.length === 0" class="text-sm text-rose-500 italic mt-2">Sin fotos. Sube nuevas imágenes.</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Añadir MÁS fotos a esta variante</label>
                            <input 
                                type="file" 
                                x-bind:name="'existing_variants[' + variant.id + '][new_images][]'" 
                                multiple 
                                accept="image/*" 
                                class="mt-2 block w-full text-sm text-slate-500 cursor-pointer file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" 
                                />
                        </div>
                    </div>
                    
                    <button type="button" @click="deleteExistingVariant(index)" class="mt-7 p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Eliminar grupo completo">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </template>

            <!-- Variantes NUEVAS -->
            <template x-for="(variant, index) in newVariants" :key="'new_'+variant.id">
                <div class="p-5 border border-sky-200 rounded-xl bg-white flex items-start gap-4 shadow-sm">
                    <div class="flex-1 grid gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Nombre de Variante (Opcional)</label>
                                <input type="text" x-bind:name="'variants[' + index + '][name]'" class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 bg-white" placeholder="Ej. Acero Inoxidable" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">SKU Variante</label>
                                <input type="text" x-bind:name="'variants[' + index + '][sku]'" class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 bg-white" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Fotografías Nuevas <span class="text-rose-500">*</span></label>
                            <input type="file" x-bind:name="'variants[' + index + '][images][]'" multiple accept="image/*" class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" required />
                        </div>
                    </div>
                    
                    <button x-show="existingVariants.length > 0 || newVariants.length > 1" type="button" @click="removeNewVariant(index)" class="mt-7 p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Cancelar adición">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </template>
        </div>
    </section>

    {{-- Estado de Publicación --}}
    <section class="border-t border-slate-100 pt-8">
        <h3 class="text-lg font-bold text-slate-800">Visibilidad</h3>
        <div class="mt-4 grid gap-6 md:grid-cols-2">
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700">Estado en Tienda</label>
                <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm" required>
                    <option value="draft" @selected(old('status', $product->status ?? 'draft') === 'draft')>Borrador (Oculto)</option>
                    <option value="active" @selected(old('status', $product->status ?? 'draft') === 'active')>Activo (Visible y Disponible)</option>
                    <option value="inactive" @selected(old('status', $product->status ?? 'draft') === 'inactive')>Agotado (Visible pero Sin Stock)</option>
                </select>
            </div>
            <div>
                <label for="is_featured" class="block text-sm font-medium text-slate-700">¿Mostrar como Producto Destacado? ❄</label>
                <select id="is_featured" name="is_featured" class="mt-1 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm" required>
                    <option value="0" @selected((int) old('is_featured', $product->is_featured ?? 0) === 0)>No</option>
                    <option value="1" @selected((int) old('is_featured', $product->is_featured ?? 0) === 1)>Sí</option>
                </select>
            </div>
        </div>
    </section>

    {{-- Acciones Finales --}}
    <div class="flex items-center gap-4 border-t border-slate-200 pt-8">
        <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg shadow-sm transition-colors">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 transition">Cancelar</a>
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
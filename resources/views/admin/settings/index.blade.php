@extends('layouts.admin-layout')

@section('title', 'Configuración del Sitio')
@section('header', 'Ajustes de la Página Pública')

@section('content')
<div x-data="{ activeTab: 'general' }" class="space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg p-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Pestañas -->
        <div class="flex border-b border-slate-200 bg-slate-50 overflow-x-auto">
            <button type="button" @click="activeTab = 'general'" :class="{'bg-white border-b-2 border-sky-600 text-sky-700 font-bold': activeTab === 'general', 'text-slate-500 hover:text-slate-700 font-medium': activeTab !== 'general'}" class="px-6 py-4 text-sm transition-colors whitespace-nowrap">
                General y Mapa
            </button>
            <button type="button" @click="activeTab = 'hero'" :class="{'bg-white border-b-2 border-sky-600 text-sky-700 font-bold': activeTab === 'hero', 'text-slate-500 hover:text-slate-700 font-medium': activeTab !== 'hero'}" class="px-6 py-4 text-sm transition-colors whitespace-nowrap">
                Panel Principal
            </button>
            <button type="button" @click="activeTab = 'bento'" :class="{'bg-white border-b-2 border-sky-600 text-sky-700 font-bold': activeTab === 'bento', 'text-slate-500 hover:text-slate-700 font-medium': activeTab !== 'bento'}" class="px-6 py-4 text-sm transition-colors whitespace-nowrap">
                Recuadros de Categorías
            </button>
            <button type="button" @click="activeTab = 'footer'" :class="{'bg-white border-b-2 border-sky-600 text-sky-700 font-bold': activeTab === 'footer', 'text-slate-500 hover:text-slate-700 font-medium': activeTab !== 'footer'}" class="px-6 py-4 text-sm transition-colors whitespace-nowrap">
                Footer y Horarios
            </button>
            <button type="button" @click="activeTab = 'cta'" :class="{'bg-white border-b-2 border-sky-600 text-sky-700 font-bold': activeTab === 'cta', 'text-slate-500 hover:text-slate-700 font-medium': activeTab !== 'cta'}" class="px-6 py-4 text-sm transition-colors whitespace-nowrap">
                Sección Final (Contáctanos)
            </button>
        </div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="p-6">
            @csrf

            <!-- Pestaña: General -->
            <div x-show="activeTab === 'general'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Número de WhatsApp</label>
                        <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '5213780000000' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                        <p class="text-xs text-slate-500 mt-1">Sin espacios ni signos `+`. Incluye el código de país (52).</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono Directo</label>
                        <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+52 000 000 0000' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                        <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'contacto@lizzycongela.com' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Dirección de la Tienda</label>
                        <input type="text" name="contact_address" value="{{ $settings['contact_address'] ?? 'Mexticacán, Jalisco, México' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                    </div>
                </div>

                <h4 class="font-bold text-slate-800 border-b border-slate-100 pb-2 mt-6">Horarios de Atención</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Lunes a Viernes</label>
                        <input type="text" name="schedule_weekdays" value="{{ $settings['schedule_weekdays'] ?? '9:00 AM - 6:00 PM' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Sábados</label>
                        <input type="text" name="schedule_saturday" value="{{ $settings['schedule_saturday'] ?? '9:00 AM - 2:00 PM' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Domingos</label>
                        <input type="text" name="schedule_sunday" value="{{ $settings['schedule_sunday'] ?? 'Cerrado' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                    </div>
                </div>
                
                <div class="mt-6 border-t border-slate-100 pt-6">
                    <h4 class="font-bold text-slate-800 mb-2">Visibilidad del Catálogo Público</h4>
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="hidden" name="catalog_only_with_images" value="0">
                            <input type="checkbox" name="catalog_only_with_images" value="1" {{ ($settings['catalog_only_with_images'] ?? '0') == '1' ? 'checked' : '' }} class="mt-1 w-5 h-5 text-sky-600 rounded border-slate-300 focus:ring-sky-500">
                            <div>
                                <span class="block text-sm font-semibold text-slate-800">Ocultar productos sin imagen en el catálogo público (mostrar solo productos con fotos)</span>
                                <span class="block text-xs text-slate-500 mt-0.5">Al activar esta casilla, los clientes que visiten la tienda no verán productos que carezcan de fotografías.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-100 pt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Google Maps (Código Iframe)</label>
                    <textarea name="google_maps_iframe" rows="4" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm font-mono text-sm" placeholder='<iframe src="https://www.google.com/maps/embed?pb=..."></iframe>'>{{ $settings['google_maps_iframe'] ?? '' }}</textarea>
                    <p class="text-xs text-slate-500 mt-1">Ve a Google Maps, busca tu negocio, dale a "Compartir" -> "Insertar un mapa" y pega el código HTML aquí.</p>
                </div>
            </div>

            <!-- Pestaña: Hero -->
            <div x-show="activeTab === 'hero'" class="space-y-6" style="display: none;">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Medalla o Etiqueta (Arriba del título)</label>
                    <input type="text" name="hero_badge" value="{{ $settings['hero_badge'] ?? '10 años de experiencia en el mercado' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Título Principal (Texto normal)</label>
                        <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? 'Soluciones en Frío y' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Texto Resaltado (En color azul)</label>
                        <input type="text" name="hero_highlight" value="{{ $settings['hero_highlight'] ?? 'Equipamiento Comercial' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Descripción corta (Debajo del título)</label>
                    <textarea name="hero_description" rows="3" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">{{ $settings['hero_description'] ?? 'Desde Mexticacán, Jalisco, "El Pueblo de los Paleteros". Encuentra congeladores, refrigeradores comerciales, neverías y mobiliario de alta durabilidad para tu negocio y tu hogar.' }}</textarea>
                </div>
            </div>

            <!-- Pestaña: Recuadros de Categorías -->
            <div x-show="activeTab === 'bento'" class="space-y-8" style="display: none;">
                <div class="border-b border-slate-200 pb-4">
                    <h3 class="text-base font-bold text-slate-800">Sección de Categorías Principales (Cuadrícula 2x2)</h3>
                    <p class="text-xs text-slate-500 mt-1">Configura el título de la sección y el contenido visual (textos, colores, imágenes y enlaces) de cada uno de los 4 recuadros destacados de la página de inicio.</p>
                </div>

                <!-- Títulos de la sección -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Título de la Sección</label>
                        <input type="text" name="bento_section_title" value="{{ $settings['bento_section_title'] ?? 'Explora por Categorías ❄' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Subtítulo / Descripción</label>
                        <input type="text" name="bento_section_subtitle" value="{{ $settings['bento_section_subtitle'] ?? 'Descubre nuestra gama de congelación comercial y mobiliario diseñados para negocios exigentes y para el hogar.' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm text-sm">
                    </div>
                </div>

                <!-- Las 4 Tarjetas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @php
                        $defaultCards = [
                            1 => ['tag' => 'Nuevas Marcas / Ofertas', 'title' => 'Congeladores Comerciales', 'color' => 'sky', 'fallback_img' => 'categories/destacada.png'],
                            2 => ['tag' => 'Línea Comercial', 'title' => 'Neveras y Paleteras', 'color' => 'emerald', 'fallback_img' => 'categories/secundaria-1.png'],
                            3 => ['tag' => 'Confort y Hogar', 'title' => 'Muebles para el Hogar', 'color' => 'orange', 'fallback_img' => 'categories/secundaria-2.png'],
                            4 => ['tag' => 'Alta Conservación', 'title' => 'Vitrinas y Conservadores', 'color' => 'purple', 'fallback_img' => 'categories/secundaria-3.png'],
                        ];
                        $colorOptions = [
                            'sky' => 'Azul Cielo (Gradiente)',
                            'emerald' => 'Verde Esmeralda (Gradiente)',
                            'orange' => 'Naranja (Gradiente)',
                            'purple' => 'Púrpura / Violeta (Gradiente)',
                            'rose' => 'Rosa / Carmín (Gradiente)',
                            'navy' => 'Azul Marino / Profundo (Gradiente)',
                            'teal' => 'Turquesa (Gradiente)',
                            'amber' => 'Ámbar / Dorado (Gradiente)',
                            'custom' => 'Color Personalizado (Hex o Gradiente CSS)',
                        ];
                    @endphp

                    @for($i = 1; $i <= 4; $i++)
                        @php
                            $def = $defaultCards[$i];
                            $currentColor = $settings["bento_box_{$i}_color"] ?? $def['color'];
                            $hasCustomImg = !empty($settings["bento_box_{$i}_image"]);
                            $currentImgUrl = $hasCustomImg 
                                ? asset('storage/' . $settings["bento_box_{$i}_image"])
                                : (file_exists(public_path('storage/' . $def['fallback_img'])) ? asset('storage/' . $def['fallback_img']) : null);
                        @endphp
                        <div x-data="{ selectedColor: '{{ $currentColor }}' }" class="p-5 bg-white rounded-xl border border-slate-200 shadow-sm space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <span class="inline-flex items-center gap-2 font-bold text-slate-800">
                                    <span class="w-6 h-6 rounded-full bg-sky-100 text-sky-700 text-xs font-bold flex items-center justify-center">{{ $i }}</span>
                                    Recuadro {{ $i }}
                                </span>
                                <span class="text-xs text-slate-400">Posición: {{ $i <= 2 ? 'Fila 1' : 'Fila 2' }}, {{ $i % 2 != 0 ? 'Columna Izq' : 'Columna Der' }}</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Etiqueta / Badge</label>
                                    <input type="text" name="bento_box_{{ $i }}_tag" value="{{ $settings["bento_box_{$i}_tag"] ?? $def['tag'] }}" class="w-full text-sm rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm" placeholder="Ej. Nuevas Marcas">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Título del Recuadro</label>
                                    <input type="text" name="bento_box_{{ $i }}_title" value="{{ $settings["bento_box_{$i}_title"] ?? $def['title'] }}" class="w-full text-sm rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm" placeholder="Ej. Congeladores Comerciales">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Color / Fondo</label>
                                    <select name="bento_box_{{ $i }}_color" x-model="selectedColor" class="w-full text-sm rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                                        @foreach($colorOptions as $val => $label)
                                            <option value="{{ $val }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div x-show="selectedColor === 'custom'">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">CSS o Hex Personalizado</label>
                                    <input type="text" name="bento_box_{{ $i }}_custom_color" value="{{ $settings["bento_box_{$i}_custom_color"] ?? '' }}" class="w-full text-sm rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm font-mono" placeholder="Ej. #2563eb o linear-gradient(...)">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría de Destino</label>
                                    <select name="bento_box_{{ $i }}_category_id" class="w-full text-sm rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                                        <option value="">-- Por defecto (automático) --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ ($settings["bento_box_{$i}_category_id"] ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">O URL Personalizada (Opcional)</label>
                                    <input type="text" name="bento_box_{{ $i }}_url" value="{{ $settings["bento_box_{$i}_url"] ?? '' }}" class="w-full text-sm rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm" placeholder="Ej. /catalogo?marca=torrey">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Imagen del Producto (PNG sin fondo recomendado)</label>
                                <div class="flex items-center gap-4 mt-1">
                                    @if($currentImgUrl)
                                        <div class="relative w-16 h-16 rounded-lg border border-slate-200 bg-slate-100 flex items-center justify-center p-1 shrink-0 overflow-hidden">
                                            <img src="{{ $currentImgUrl }}" alt="Vista previa" class="max-w-full max-h-full object-contain">
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <input type="file" name="bento_box_{{ $i }}_image_file" accept="image/png,image/jpeg,image/webp" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 cursor-pointer">
                                        <p class="text-[11px] text-slate-400 mt-1">Sube una imagen para reemplazar la actual (PNG transparente de alta calidad).</p>
                                    </div>
                                </div>
                                @if($hasCustomImg)
                                    <label class="inline-flex items-center gap-2 mt-2 text-xs text-rose-600 cursor-pointer">
                                        <input type="checkbox" name="bento_box_{{ $i }}_remove_image" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        <span>Quitar imagen personalizada y volver a la predeterminada</span>
                                    </label>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Pestaña: Footer y Horarios -->
            <div x-show="activeTab === 'footer'" class="space-y-6" style="display: none;">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Descripción del Footer</label>
                    <textarea name="footer_desc" rows="3" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">{{ $settings['footer_desc'] ?? 'Con más de 10 años de experiencia, brindamos soluciones de calidad en congeladores, refrigeración comercial y mobiliario. Orgullosamente desde Mexticacán, Jalisco, "El Pueblo de los Paleteros".' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Eslogan del Footer</label>
                    <input type="text" name="footer_slogan" value="{{ $settings['footer_slogan'] ?? 'Equipa tu negocio con la mejor tecnología en frío.' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                </div>
            </div>

            <!-- Pestaña: CTA -->
            <div x-show="activeTab === 'cta'" class="space-y-6" style="display: none;">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Título de Sección (Antes del mapa)</label>
                    <input type="text" name="cta_title" value="{{ $settings['cta_title'] ?? '¿Listo para equipar tu negocio con tecnología de frío?' }}" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Descripción del Llamado a la Acción</label>
                    <textarea name="cta_description" rows="3" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500 shadow-sm">{{ $settings['cta_description'] ?? 'Contáctanos hoy mismo. Te brindamos asesoría personalizada en congeladores, refrigeradores comerciales y mobiliario.' }}</textarea>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg shadow-sm transition-colors">
                    Guardar toda la configuración
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
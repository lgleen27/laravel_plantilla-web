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
            <button type="button" @click="activeTab = 'footer'" :class="{'bg-white border-b-2 border-sky-600 text-sky-700 font-bold': activeTab === 'footer', 'text-slate-500 hover:text-slate-700 font-medium': activeTab !== 'footer'}" class="px-6 py-4 text-sm transition-colors whitespace-nowrap">
                Footer y Horarios
            </button>
            <button type="button" @click="activeTab = 'cta'" :class="{'bg-white border-b-2 border-sky-600 text-sky-700 font-bold': activeTab === 'cta', 'text-slate-500 hover:text-slate-700 font-medium': activeTab !== 'cta'}" class="px-6 py-4 text-sm transition-colors whitespace-nowrap">
                Sección Final (Contáctanos)
            </button>
        </div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('admin.settings.update') }}" class="p-6">
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
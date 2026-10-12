<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Obtenemos todas las configuraciones y las enviamos a la vista
        // pluck('value', 'key') convierte la base de datos en un arreglo simple: ['whatsapp_number' => '52...']
        $settings = Setting::pluck('value', 'key')->toArray();
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Excluimos los tokens de seguridad internos de Laravel
        $data = $request->except(['_token', '_method']);

        // Guardamos o actualizamos cada campo que haya en el formulario
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Configuración del sitio actualizada correctamente.');
    }
}
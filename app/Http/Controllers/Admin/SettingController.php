<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Obtenemos todas las configuraciones y las enviamos a la vista
        // pluck('value', 'key') convierte la base de datos en un arreglo simple: ['whatsapp_number' => '52...']
        $settings = Setting::pluck('value', 'key')->toArray();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.settings.index', compact('settings', 'categories'));
    }

    public function update(Request $request)
    {
        // Procesamos la carga o eliminación de imágenes de los 4 recuadros de categorías
        for ($i = 1; $i <= 4; $i++) {
            $imageKey = "bento_box_{$i}_image";
            $fileInput = "bento_box_{$i}_image_file";
            $removeInput = "bento_box_{$i}_remove_image";

            if ($request->boolean($removeInput)) {
                $currentImage = Setting::where('key', $imageKey)->value('value');
                if ($currentImage && Storage::disk('public')->exists($currentImage)) {
                    Storage::disk('public')->delete($currentImage);
                }
                Setting::updateOrCreate(
                    ['key' => $imageKey],
                    ['value' => null]
                );
            } elseif ($request->hasFile($fileInput) && $request->file($fileInput)->isValid()) {
                // Eliminar imagen anterior subida si existe
                $currentImage = Setting::where('key', $imageKey)->value('value');
                if ($currentImage && Storage::disk('public')->exists($currentImage)) {
                    Storage::disk('public')->delete($currentImage);
                }

                $storedPath = $request->file($fileInput)->store('categories', 'public');
                Setting::updateOrCreate(
                    ['key' => $imageKey],
                    ['value' => $storedPath]
                );
            }
        }

        // Excluimos los tokens de seguridad y los inputs temporales de archivo
        $data = $request->except([
            '_token',
            '_method',
            'bento_box_1_image_file',
            'bento_box_1_remove_image',
            'bento_box_2_image_file',
            'bento_box_2_remove_image',
            'bento_box_3_image_file',
            'bento_box_3_remove_image',
            'bento_box_4_image_file',
            'bento_box_4_remove_image',
        ]);

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
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Solo permitimos que se llenen estos dos campos
    protected $fillable = ['key', 'value'];

    /**
     * Función rápida para obtener el valor de una configuración.
     * Si la llave no existe, retorna el valor por defecto ($default).
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Intentamos cargar las configuraciones globales
        try {
            // Primero revisamos que la tabla exista (útil si mueves el proyecto de cero)
            if (Schema::hasTable('settings')) {
                // Obtenemos todas las configuraciones en formato de arreglo simple
                $siteSettings = Setting::pluck('value', 'key')->toArray();
                
                // Compartimos la variable '$siteSettings' con TODAS las vistas de Laravel
                View::share('siteSettings', $siteSettings);
            }
        } catch (\Exception $e) {
            // Si hay un problema de base de datos, lo ignoramos para que no colapse el sistema 
            // y te permita seguir ejecutando comandos en la consola.
        }
    }
}
<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        $this->configureTrustProxies();

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

    /**
     * Configura los proxies de confianza a partir de config('app.trust_proxies').
     */
    protected function configureTrustProxies(): void
    {
        $value = config('app.trust_proxies');

        if (is_string($value)) {
            $normalized = strtolower(trim($value));

            if ($normalized === '' || $normalized === 'false' || $normalized === '0') {
                return;
            }

            if ($normalized === 'true' || $normalized === '1' || $normalized === '*') {
                TrustProxies::at('*');

                return;
            }

            $proxies = array_values(array_filter(array_map('trim', explode(',', $value))));

            if ($proxies !== []) {
                TrustProxies::at($proxies);
            }

            return;
        }

        if ($value === null || $value === false || $value === 0) {
            return;
        }

        if ($value === true || $value === 1) {
            TrustProxies::at('*');

            return;
        }

        if (is_array($value) && $value !== []) {
            TrustProxies::at($value);
        }
    }
}
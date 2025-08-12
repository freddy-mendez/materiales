<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Usuario;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('manage-colaboradores', function (Usuario $user) {
            return $user->rol === 'administrador';
        });

        Gate::define('manage-inventario', function (Usuario $user) {
            return $user->rol === 'administrador';
        });
        
        // Nuevo Gate para gestionar las entregas
        Gate::define('manage-entregas', function (Usuario $user) {
            return $user->rol === 'administrador';
        });
    }
}

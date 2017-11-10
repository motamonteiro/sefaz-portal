<?php

namespace MotaMonteiro\Sefaz\Portal\Providers;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PortalServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::middleware(['web'])->group(__DIR__ . '/../Routes/web.php');

        Route::namespace('MotaMonteiro\Sefaz\Portal\Http\Controllers')->middleware(['web'])->group(__DIR__ . '/../Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../Views', 'Portal');

        $this->publishes([
            __DIR__ . '/../Views' => resource_path('views/vendor/Portal'),
        ], 'Portal:views');

        $this->publishes([
            __DIR__ . '/../Lang' => resource_path('lang/vendor/Portal'),
        ], 'Portal:lang');

        $this->publishes([
            __DIR__ . '/../config/sistema.php' => config_path('sistema.php'),
        ], 'Portal:config');
        $this->publishes([
            __DIR__ . '/../public/assets/controller.js' => public_path('vendor/js/controller.js'),
            __DIR__ . '/../public/assets/msg.js' => public_path('vendor/js/msg.js'),
        ], 'Portal:public');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/sistema.php',
            'sistema'
        );
    }

}
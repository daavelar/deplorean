<?php

namespace Laravocker\Providers;

use Illuminate\Support\ServiceProvider;

class LaravockerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/laravocker.php' => config_path('laravocker.php'),
        ]);
    }
}

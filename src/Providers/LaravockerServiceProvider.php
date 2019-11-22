<?php

namespace Laravocker\Providers;

use Illuminate\Support\ServiceProvider;
use Laravocker\Commands\LaravockerSetup;
use Laravocker\Commands\LaravockerUp;

class LaravockerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        dump('Resistrando');
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

        if ($this->app->runningInConsole()) {
            $this->commands([
                LaravockerSetup::class,
                LaravockerUp::class,
            ]);
        }
    }
}

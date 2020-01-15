<?php

namespace Deplrean\Providers;

use Illuminate\Support\ServiceProvider;
use Deplorean\Commands\DeploreanSetup;
use Deplorean\Commands\DeploreanUp;

class DeploreanServiceProvider extends ServiceProvider
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
            __DIR__ . '/Config/deplorean.php' => config_path('deplorean.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                DeploreanSetup::class,
                DeploreanUp::class,
            ]);
        }
    }
}

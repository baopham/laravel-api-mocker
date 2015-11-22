<?php

namespace BaoPham\ApiMocker;

use Illuminate\Support\ServiceProvider;

/**
 * Class ApiMockerServiceProvider
 * @package BaoPham\ApiMocker
 */
class ApiMockerServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/apimocker.php' => config_path('apimocker.php'),
        ], 'config');

        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

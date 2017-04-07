<?php

namespace Clarkeash\Doorman\Providers;

use Clarkeash\Doorman\Doorman;
use Illuminate\Support\ServiceProvider;

class DoormanServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../resources/config/doorman.php' => config_path('doorman.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../../resources/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../resources/config/doorman.php',
            'doorman'
        );

        $this->app->bind('doorman', Doorman::class);
    }
}

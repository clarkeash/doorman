<?php

namespace Clarkeash\Doorman\Providers;

use Clarkeash\Doorman\Doorman;
use Illuminate\Support\ServiceProvider;

class DoormanServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/doorman.php' => config_path('doorman.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../../migrations');
    }

    public function register()
    {
        $this->app->bind('doorman', Doorman::class);
    }
}

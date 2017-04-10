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
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../resources/translations' => resource_path('lang/vendor/doorman'),
        ], 'translations');

        $this->publishes([
            __DIR__ . '/../../resources/migrations' => database_path('migrations')
        ], 'migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../../resources/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/translations', 'doorman');
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

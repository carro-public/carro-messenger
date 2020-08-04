<?php

namespace CarroPublic\CarroMessenger;

use Illuminate\Support\ServiceProvider;

class CarroMessengerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'carropublic');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'carropublic');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/carromessenger.php', 'carromessenger');

        // Register the service the package provides.
        $this->app->singleton('carromessenger', function ($app) {
            return new CarroMessenger;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['carromessenger'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/carromessenger.php' => config_path('carromessenger.php'),
        ], 'carromessenger.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/carropublic'),
        ], 'carromessenger.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/carropublic'),
        ], 'carromessenger.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/carropublic'),
        ], 'carromessenger.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}

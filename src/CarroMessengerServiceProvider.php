<?php

namespace CarroPublic\CarroMessenger;

use Illuminate\Support\ServiceProvider;
use CarroPublic\CarroMessenger\Providers\EventServiceProvider;
use CarroPublic\CarroMessenger\Messaging\SmsTwoWay\SmsTelerivet;
use CarroPublic\CarroMessenger\Messaging\WhatsApp\WhatsAppTwilio;
use CarroPublic\CarroMessenger\Messaging\WhatsApp\WhatsAppMessageBird;

class CarroMessengerServiceProvider extends ServiceProvider
{
    /**
     * Commands that are needed to register
     * 
     * @var array $commands
     */
    private $commands = [
        'CarroPublic\CarroMessenger\Commands\WhatsAppWebhook',
        'CarroPublic\CarroMessenger\Commands\TokyWebhook',
    ];

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
        $this->mergeConfigFrom(__DIR__.'/../config/carro_messenger.php', 'carro_messenger');

        // Register the service the package provides.
        $this->app->singleton(
            'carromessenger', function ($app) {
                return new CarroMessenger;
            }
        );

        // Register the messagebird service
        $this->app->singleton(
            'whatsappmessagebird', function ($app) {
                return new WhatsAppMessageBird;
            }
        );

        // Register the twilio service
        $this->app->singleton(
            'whatsapptwilio', function ($app) {
                return new WhatsAppTwilio;
            }
        );

        // Register the telerivet service
        $this->app->singleton(
            'smstelerivet', function ($app) {
                return new SmsTelerivet;
            }
        );

        $this->commands($this->commands);
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
        $this->publishes(
            [
            __DIR__.'/../config/carro_messenger.php' => config_path('carro_messenger.php'),
            ], 'carro_messenger.config'
        );
    }
}

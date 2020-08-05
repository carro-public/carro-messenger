<?php

namespace CarroPublic\CarroMessenger\Providers;

use CarroPublic\CarroMessenger\Events\MessageWasSent;
use CarroPublic\CarroMessenger\Listeners\UpdateMessageSentModel;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MessageWasSent::class => [
            UpdateMessageSentModel::class,
        ]
    ];

    /**
     * Register any events for application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
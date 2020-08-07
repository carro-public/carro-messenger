<?php

namespace CarroPublic\CarroMessenger\Facades;

use Illuminate\Support\Facades\Facade;

class WhatsAppTwilio extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'whatsapptwilio';
    }
}

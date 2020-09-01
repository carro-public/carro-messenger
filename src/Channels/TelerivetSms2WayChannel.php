<?php

namespace CarroPublic\CarroMessenger\Channels;

use Illuminate\Notifications\Notification;

class TelerivetSms2WayChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * 
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        return $notification->toSms2Way($notifiable);
    }
}
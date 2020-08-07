<?php

namespace CarroPublic\CarroMessenger\Channels;

use Illuminate\Notifications\Notification;

class MessageBirdWhatsAppMessageChannel
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
        return $notification->toWhatsApp($notifiable);
    }
}
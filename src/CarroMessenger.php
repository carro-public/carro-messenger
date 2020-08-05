<?php

namespace CarroPublic\CarroMessenger;

use Notification;
use CarroPublic\CarroMessenger\NotificationServices\WhatsAppMessageBirdNotification;

class CarroMessenger
{
    /**
     * Send message multiple channels using multiple third party services
     *
     * @param array $data
     * 
     * @return void
     */
    public function sendMessage($data)
    {
        $messagingService = $this->getMessagingService(data_get($data, 'service'), data_get($data, 'channel'));
        if (is_null($messagingService)) {
            return;
        }

        return Notification::route(data_get($data, 'to'), data_get($data, 'from'))
            ->notify(
                new $messagingService($data)
            );
    }

    /**
     * Deciding which messaging service to be used base on channel and third party service
     * 
     * @param string $channel
     * @param string $service
     * 
     * @return $messagingService|null
     */
    public function getMessagingService($service, $channel)
    {
        $messagingService = $service.'-'.$channel;

        $services = [
            'messagebird-whatsapp' => WhatsAppMessageBirdNotification::class
        ];

        return data_get($services, $messagingService);
    }
}
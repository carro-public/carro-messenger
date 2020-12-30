<?php

namespace CarroPublic\CarroMessenger;

use Exception;
use Notification;
use Illuminate\Support\Facades\Log;
use CarroPublic\CarroMessenger\Events\MessageWasSent;
use CarroPublic\CarroMessenger\Facades\WhatsAppMessageBird;
use CarroPublic\CarroMessenger\Common\MessageFailedResponse;
use CarroPublic\CarroMessenger\NotificationServices\WhatsAppTwilioNotification;
use CarroPublic\CarroMessenger\NotificationServices\Sms2WayTelerivetNotification;
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
     * Send whatsapp template message with Message Bird
     *
     * @param array $data
     * 
     * @return void
     */
    public function sendWhatsAppTemplateSMSViaMsgBird($data)
    {
        $model = data_get($data, 'model');

        try {
            $response = WhatsAppMessageBird::sendTemplateMessage($data);

            if (config('carro_messenger.event_is_called') && !is_null($model)) {
                event(new MessageWasSent($model, $response));
            }

            return $response;
        } catch (Exception $e) {
            Log::error('WhatsApp template message failed @'. __FUNCTION__.' of '. __CLASS__,
            [$e->getMessage()]);
            
            event(new MessageWasSent($model, new MessageFailedResponse()));
        }
        
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
            'messagebird-whatsapp'  => WhatsAppMessageBirdNotification::class,
            'twilio-whatsapp'       => WhatsAppTwilioNotification::class,
            'telerivet-sms2way'     => Sms2WayTelerivetNotification::class,
        ];

        return data_get($services, $messagingService);
    }
}
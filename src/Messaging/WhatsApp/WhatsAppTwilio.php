<?php

namespace CarroPublic\CarroMessenger\Messaging\WhatsApp;

use Twilio\Rest\Client;

class WhatsAppTwilio
{
    /**
     * Twilio Client
     *
     * @var Client
     */
    protected $client;
    
    /**
     * Initialize Twilio account sid and auth token
     */
    public function __construct()
    {
        $sid    = config('carromessenger.twilio.account_sid');
        $token  = config('carromessenger.twilio.auth_token');

        $this->client = new Client($sid, $token); 
    }

    /**
     * Send SMS
     * 
     * @param string $to
     * @param string $message
     * @param string $from
     * 
     * @return object $message
     */
    public function sendSMS($to, $message, $from=null)
    {
        $message = $this->client->messages->create(
            $to,
            [
                'from' => $from ?: config('carromessenger.twilio.from'),
                'body' => $message
            ]
        );

        return $message;
    }

    /**
     * Send whatsapp sms
     *
     * @param string $to
     * @param string $message
     * @param string $from
     * @param array  $mediaUrl
     * @param string $prefix
     * 
     * @return object $message
     */
    public function sendWhatsAppSMS($to, $message, $mediaUrl=[], $from=null, $prefix='whatsapp:')
    {
        $message = $this->client->messages->create(
            $prefix . $to,
            [
                'from' => $prefix . ($from?: config('carromessenger.twilio.whatsapp_from')),
                'body' => $message,
                'mediaUrl' => $mediaUrl
            ]
        );

        return $message;
    }
}
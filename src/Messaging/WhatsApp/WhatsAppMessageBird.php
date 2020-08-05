<?php

namespace CarroPublic\CarroMessenger\Messaging\WhatsApp;

use MessageBird\Client;
use Illuminate\Support\Facades\Log;
use MessageBird\Objects\Conversation\Content;
use MessageBird\Objects\Conversation\SendMessage;

class WhatsAppMessageBird
{
    /**
     * MessageBird client
     *
     * @var \MessageBird\Client
     */
    private $messageBirdClient;

    /**
     * WhatsApp ChannelId
     *
     * @var string
     */
    private $whatsAppchannelId;

    /**
     * Setting MessageBird client and ChannelId
     */
    public function __construct()
    {
        $this->messageBirdClient    = new Client(config('carromessenger.message_bird.access_key'));
        $this->whatsAppchannelId    = config('carromessenger.message_bird.whatsapp_channel_id');
    }

    /**
     * Sending whatsApp message
     * 
     * @param string $to
     * @param string $message
     *
     * @return Object
     */
    public function sendWhatsAppText($to, $message)
    {
        $content = new Content();
        $content->text = $message;

        $sendMessage = new SendMessage();
        $sendMessage->from = $this->whatsAppchannelId;
        $sendMessage->to = $to;
        $sendMessage->content = $content;

        $sendMessage->type = 'text';

        try {
            return $this->messageBirdClient->conversationSend->send($sendMessage);
        } catch (\Exception $e) {
            echo sprintf("%s: %s", get_class($e), $e->getMessage());
        }
    }

    /**
     * Sending whatsApp message
     * 
     * @param string $to
     * @param string $imageUrl
     * @param string $caption
     *
     * @return Object
     */
    public function sendWhatsAppImage($to, $imageUrl, $caption = null)
    {
        $content = new Content();
        $content->image = [
            'url'       => $imageUrl,
            'caption'   => $caption,
        ];

        $sendMessage = new SendMessage();
        $sendMessage->from = $this->whatsAppchannelId;
        $sendMessage->to = $to;
        $sendMessage->content = $content;

        $sendMessage->type = 'image';

        try {
            return $this->messageBirdClient->conversationSend->send($sendMessage);
        } catch (\Exception $e) {
            Log::error("%s: %s", get_class($e), $e->getMessage());
        }
    }
}
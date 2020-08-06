<?php

namespace CarroPublic\CarroMessenger\Messaging\WhatsApp;

use MessageBird\Client;
use Illuminate\Support\Facades\Log;
use MessageBird\Objects\Conversation\Content;
use MessageBird\Objects\Conversation\HSM\Params;
use MessageBird\Objects\Conversation\HSM\Message;
use MessageBird\Objects\Conversation\SendMessage;
use MessageBird\Objects\Conversation\HSM\Language;

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

    /**
     * Sending template message
     * 
     * @param array $data
     * 
     * @return $mixed
     */
    public function sendTemplateMessage($data)
    {
        $content = new Content();
        $hsmMessage = new Message();

        $params = data_get($data, 'params');

        $hsmParams = [];

        foreach ($params as $param) {
            $hsmParam = new Params();
            $hsmParam->default = $param;
            array_push($hsmParams, $hsmParam);
        }

        $hsmLanguage = new Language();
        $hsmLanguage->policy = Language::DETERMINISTIC_POLICY;
        $hsmLanguage->code = data_get($data, 'language_code');

        $hsmMessage->templateName = data_get($data, 'template_name');//'support';
        $hsmMessage->namespace = config('carromessenger.message_bird.whatsapp_template_namespace');
        $hsmMessage->params = $hsmParams;
        $hsmMessage->language = $hsmLanguage;

        $content->hsm = $hsmMessage;

        $message = new Message();
        $message->channelId = $this->whatsAppchannelId;
        $message->content = $content;
        $message->to = data_get($data, 'to');
        $message->type = 'hsm';

        try {
            return $this->messageBirdClient->conversations->start($message);
        } catch (Exception $e) {
            echo sprintf("%s: %s", get_class($e), $e->getMessage());
        }

    }
}
<?php

namespace CarroPublic\CarroMessenger\Messaging\WhatsApp;

use MessageBird\Client;
use Illuminate\Support\Facades\Log;
use MessageBird\Objects\Conversation\Content;
use MessageBird\Objects\Conversation\HSM\Message;
use MessageBird\Objects\Conversation\HSM\Params;
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
        $this->messageBirdClient    = new Client(config('carro_messenger.message_bird.access_key'));
        $this->whatsAppchannelId    = config('carro_messenger.message_bird.whatsapp_channel_id');
    }

    /**
     * Sending whatsApp message
     * 
     * @param string $to
     * @param string $message
     * @param string $reportUrl
     *
     * @return Object
     */
    public function sendWhatsAppText($to, $message, $reportUrl = null)
    {
        $content = new Content();
        $content->text = $message;

        $sendMessage = new SendMessage();
        $sendMessage->from = $this->whatsAppchannelId;
        $sendMessage->to = $to;
        $sendMessage->reportUrl = $reportUrl;
        $sendMessage->content = $content;

        $sendMessage->type = 'text';

        try {
            return $this->messageBirdClient->conversationSend->send($sendMessage);
        } catch (\Exception $e) {
            Log::error('Whatsapp Text message failed @'.__FUNCTION__.' of '.__CLASS__,
            [$e->getMessage()]);
        }
    }

    /**
     * Sending WhatsApp media message
     * 
     * @param string $to
     * @param string $mediaUrl
     * @param string $caption
     * @param string $reportUrl
     *
     * @return Object
     */
    public function sendWhatsAppMedia($to, $mediaUrl, $caption = null, $reportUrl = null)
    {
        $content = new Content();

        $data = [
            'url'       => $mediaUrl,
            'caption'   => $caption,
        ];

        $type = 'image';

        if(@exif_imagetype($mediaUrl)) {
            $content->image = $data;
        } else {
            $content->file = $data;
            $type = 'file';
        }

        $sendMessage = new SendMessage();
        $sendMessage->from = $this->whatsAppchannelId;
        $sendMessage->to = $to;
        $sendMessage->reportUrl = $reportUrl;
        $sendMessage->content = $content;

        $sendMessage->type = $type;

        try {
            return $this->messageBirdClient->conversationSend->send($sendMessage);
        } catch (\Exception $e) {
            Log::error('WhatsApp Media message failed @'.__FUNCTION__.' of '.__CLASS__,
            [$e->getMessage()]);
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

        $hsmMessage->templateName = data_get($data, 'template_name');
        $hsmMessage->namespace = config('carro_messenger.message_bird.whatsapp_template_namespace');
        $hsmMessage->params = $hsmParams;
        $hsmMessage->language = $hsmLanguage;

        $content->hsm = $hsmMessage;

        $message = new SendMessage();
        $message->content = $content;
        $message->from = $this->whatsAppchannelId;
        $message->to = data_get($data, 'to');
        $message->type = 'hsm';
        $message->reportUrl = data_get($data, 'report_url');

        try {
            return $this->messageBirdClient->conversationSend->send($message);
        } catch (Exception $e) {
            Log::error('Whatsapp Template message failed @'.__FUNCTION__.' of '.__CLASS__,
            [$e->getMessage()]);
        }
    }
}
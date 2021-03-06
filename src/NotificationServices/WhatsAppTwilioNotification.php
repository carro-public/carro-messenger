<?php

namespace CarroPublic\CarroMessenger\NotificationServices;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use CarroPublic\CarroMessenger\Events\MessageWasSent;
use CarroPublic\CarroMessenger\Facades\WhatsAppTwilio;
use CarroPublic\CarroMessenger\Common\MessageFailedResponse;
use CarroPublic\CarroMessenger\Channels\TwilioWhatsAppMessageChannel;

class WhatsAppTwilioNotification extends Notification
{
    use Queueable;

    /**
     * Phone number to send message
     * 
     * @var string $to
     */
    protected $to;

    /**
     * Message
     * 
     * @var string $message
     */
    protected $message;

    /**
     * URL
     * 
     * @var string $mediaUrl
     */
    protected $mediaUrl;

     /**
      * message send model
      * 
      * @var Model $model
      */
    protected $model;

    /**
     * From
     * 
     * @var string $from
     */
    protected $from;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->to       = data_get($data, 'to');
        $this->message  = data_get($data, 'message');
        $this->mediaUrl = data_get($data, 'media_url');
        $this->model    = data_get($data, 'model');
        $this->from     = data_get($data, 'from');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     */
    public function via($notifiable)
    {
        return [TwilioWhatsAppMessageChannel::class];
    }

    /**
     * Sending whatsapp message using messageBird
     *
     * @param $notifiable
     * 
     * @return void
     */
    public function toWhatsApp($notifiable)
    {
        try {
            $mediaUrl = is_null($this->mediaUrl) ? [] : [$this->mediaUrl];

            $response = WhatsAppTwilio::sendWhatsAppSMS($this->to, $this->message, $mediaUrl, $this->from);
            $this->handleMessageSentEvent($response);
        } catch (Exception $e) {
            Log::error('WhatsApp message failed@'.__FUNCTION__. ' of '.__CLASS__,
            [$e->getMessage()]);
            
            event(new MessageWasSent($this->model, new MessageFailedResponse()));
        }
        
    }

    /**
     * Handling MessageWasSend event
     * 
     * @param string $messageId
     * 
     * @return void
     */
    private function handleMessageSentEvent($response)
    {
        $model = $this->model;

        if (config('carro_messenger.event_is_called') && !is_null($model)) {
            event(new MessageWasSent($model, $response));
        }
    }
}

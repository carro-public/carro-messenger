<?php

namespace CarroPublic\CarroMessenger\NotificationServices;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use CarroPublic\CarroMessenger\Events\MessageWasSent;
use CarroPublic\CarroMessenger\Facades\WhatsAppTwilio;
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
     * @var string $imageUrl
     */
    protected $imageUrl;

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
        $this->imageUrl = data_get($data, 'image_url');
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
        $message = WhatsAppTwilio::sendWhatsAppSMS($this->to, $this->message, [$this->imageUrl], $this->from);
        $this->handleMessageSentEvent($message->id);
    }

    /**
     * Handling MessageWasSend event
     * 
     * @param string $messageId
     * 
     * @return void
     */
    private function handleMessageSentEvent($messageId)
    {
        $model = $this->model;

        if (config('carro_messenger.event_is_called') && !is_null($model)) {
            event(new MessageWasSent($model, $messageId));
        }
    }
}

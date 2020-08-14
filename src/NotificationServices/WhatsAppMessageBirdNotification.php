<?php

namespace CarroPublic\CarroMessenger\NotificationServices;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\Communication\Models\Communication;
use CarroPublic\CarroMessenger\Events\MessageWasSent;
use CarroPublic\CarroMessenger\Facades\WhatsAppMessageBird;
use CarroPublic\CarroMessenger\Channels\MessageBirdWhatsAppMessageChannel;


class WhatsAppMessageBirdNotification extends Notification
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
     * report url 
     * 
     * @var string $reportUrl
     */
    protected $reportUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->to       = data_get($data, 'to');
        $this->message  = data_get($data, 'message');
        $this->imageUrl = data_get($data, 'imageurl');
        $this->model    = data_get($data, 'model');
        $this->reportUrl = data_get($data, 'report_url');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     */
    public function via($notifiable)
    {
        return [MessageBirdWhatsAppMessageChannel::class];
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
        if (is_null($this->imageUrl)) {
            $response = WhatsAppMessageBird::sendWhatsAppText($this->to, $this->message, $this->reportUrl);
            return $this->handleMessageSentEvent($response);
        }

        $response = WhatsAppMessageBird::sendWhatsAppImage($this->to, $this->imageUrl, $this->message, $this->reportUrl);
        $this->handleMessageSentEvent($response);
    }

    /**
     * Handling MessageWasSend event
     * 
     * @param $response
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

<?php

namespace CarroPublic\CarroMessenger\Commands;

use MessageBird\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use MessageBird\Objects\Conversation\Webhook;

class WhatsAppWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:message-bird:webhooks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage whatsapp messagebird webhooks';

    /**
     * Toky app url
     *
     * @var string
     */
    protected $appUrl;

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->appUrl = config('carro_messenger.webhook_url.webhook_url');

        $this->messageBirdClient    = new Client(config('carro_messenger.message_bird.access_key'));

        $this->whatsAppchannelId    = config('carro_messenger.message_bird.whatsapp_channel_id');

        $actions = ['list', 'get', 'create', 'delete'];

        $action = $this->choice('What kind of event action you want to perform', $actions, 'list');

        call_user_func(__NAMESPACE__ . '\WhatsAppWebhook::'.$action);
    }

    /**
     * Performing create a new toky event
     */
    private function create()
    {
        $events = [
            Webhook::EVENT_CONVERSATION_CREATED,
            Webhook::EVENT_CONVERSATION_UPDATED,
            Webhook::EVENT_MESSAGE_CREATED,
            Webhook::EVENT_MESSAGE_UPDATED,
        ];

        $chosenEvents = $this->choice(
            'What kind of event you want to create',
            $events,
            $defaultIndex = null,
            $maxAttempts = null,
            $allowMultipleSelections = true
        );

        $webhookUrl = $this->ask('Please enter the webhook URL'); 

        $webhook = new Webhook();
        $webhook->events    = $chosenEvents;
        $webhook->channelId = $this->whatsAppchannelId;
        $webhook->url       = $webhookUrl;

        try {
            $response = $this->messageBirdClient->conversationWebhooks->create($webhook);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Getting Event list from toky
     */
    private function list()
    {
        try {
            $response = $this->messageBirdClient->conversationWebhooks->getList();

            if (data_get($response, 'totalCount') == 0) {
                $this->info("There is no webhooks for now");
                return;
            }

            $items = $response->items;

            $data = [];
            foreach ($items as $item) {
                array_push($data, $this->getFetchedData($item));
            }

            $headers = ['webhook_id', 'href', 'channel_id', 'events', 'url', 'created_at'];
            $this->table($headers, $data);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Delete Event from toky event list
     */
    private function delete()
    {
        $webhookId = $this->ask('Please enter Webhook ID');

        try {
            $response = $this->messageBirdClient->conversationWebhooks->delete($webhookId);

            if ($response) {
                $this->info("WebhookId {$webhookId} is deleted successfully");
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Get Event from toky event list
     */
    private function get()
    {
        $webhookId = $this->ask('Please enter Webhook ID');

        try {
            $response = $this->messageBirdClient->conversationWebhooks->read($webhookId);

            $data = [];
            if ($response) {
                array_push($data, $this->getFetchedData($response));

                $headers = ['webhook_id', 'href', 'channel_id', 'events', 'url', 'created_at'];
                $this->table($headers, $data);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Fetch response from MessageBird to display with table
     * 
     * @param \MessageBird\Objects\Conversation\Webhook
     * 
     * @return array $itemData
     */
    public function getFetchedData($item)
    {
        return [
            'webhook_id'    => $item->id,
            'href'          => $item->href,
            'channel_id'    => $item->channelId,
            'events'        => implode(" ", $item->events),
            'url'           => $item->url,
            'created_at'    => $item->createdDatetime,
        ];
    }
}
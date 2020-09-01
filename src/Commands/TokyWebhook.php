<?php

namespace CarroPublic\CarroMessenger\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TokyWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toky:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To manage toky webhook events';

    /**
     * Toky app key
     *
     * @var string
     */
    protected $appKey;

    /**
     * Toky app url
     *
     * @var string
     */
    protected $appUrl;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->appKey = config('carro_messenger.toky.app_key');

        $this->appUrl = config('carro_messenger.toky.app_url');

        $actions = ['get', 'create', 'delete'];

        $action = $this->choice('What kind of event action you want to perform', $actions, 'get');

        call_user_func(__NAMESPACE__ . '\TokyWebhook::'.$action);
    }

    /**
     * Performing create a new toky event
     */
    private function create()
    {
        $events = ['new_call', 'new_voicemail', 'new_sms'];

        $event = $this->choice('What kind of event you want to create', $events);
        $webhookUrl = $this->ask('Please enter the webhook URL');
        
        $response = Http::withHeaders([
            'X-Toky-Key' => $this->appKey,
        ])->post($this->appUrl, [
            'hook_url' => $webhookUrl,
            'event' => $event,
        ]);

        if ($response->status() == 201) {
            $this->info("{$event} is created successfully");

            if ($this->confirm('Would you like to create more event?')) {
                $this->create();
            }
        }

        $this->error(data_get($response->json(), 'message'));
    }

    /**
     * Getting Event list from toky
     */
    private function get()
    {
        $response = Http::withHeaders([
            'X-Toky-Key' => $this->appKey,
        ])->get($this->appUrl);

        $data = $response->json();

        if (data_get($data, 'count') == 0) {
            $this->info("There is no events for now");
            return;
        }

        $headers = ['id', 'event', 'hook_url', 'created_at'];
        $this->table($headers, data_get($data, 'results'));
    }

    /**
     * Delete Event from toky event list
     */
    private function delete()
    {
        $eventId = $this->ask('Please enter event ID');

        $response = Http::withHeaders([
            'X-Toky-Key' => $this->appKey,
        ])->delete("{$this->appUrl}/{$eventId}");

        if ($response->status() == 200) {
            $this->info("{$eventId} is deleted successfully");
            
            if ($this->confirm('Would you like to delete more event?')) {
                $this->delete();
            }
        }

        $this->error(data_get($response->json(), 'message'));
    }
}

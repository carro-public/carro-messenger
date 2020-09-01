<?php

namespace CarroPublic\CarroMessenger\Messaging\SmsTwoWay;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsTelerivet
{
    /**
     * @var string $apiKey
     */
    private $apiKey;

    /**
     * @var string $projectId
     */
    private $projectId;

    /**
     * Setting Telerivet API key and project id
     */
    public function __construct()
    {
        $this->apiKey       = config('carro_messenger.telerivet.api_key');
        $this->projectId   = config('carro_messenger.telerivet.project_id');
    }

    /**
     * Sending sms2way to user
     * 
     * @param string $to
     * @param string $content
     * 
     * @return \Illuminate\Http\Client\Response|void
     */
    public function sendSmsTwoWay($to, $content)
    {
        try {
            $response = Http::post("https://api.telerivet.com/v1/projects/{$this->projectId}/messages/send", [
                'api_key'      => $this->apiKey,
                'content'      => $content,
                'to_number'    => $to,
            ]);

            return $response->body();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }   
    }
}
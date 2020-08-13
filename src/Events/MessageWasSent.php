<?php

namespace CarroPublic\CarroMessenger\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class MessageWasSent
{
    use Dispatchable, SerializesModels;

    public $sentModel;

    /**
     * Intiating a new instacnce for MessageWasSent
     *
     * @param Model  $sendModel
     * @param mix $response
     */
    public function __construct($sendModel, $response)
    {
        $this->sentModel = $sendModel;
        $this->response = $response;
    }
}
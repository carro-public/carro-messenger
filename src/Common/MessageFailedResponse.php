<?php

namespace CarroPublic\CarroMessenger\Common;

class MessageFailedResponse
{
    /**
     * Message status
     */
    public $status;

    public function __construct()
    {
        $this->status = 'failed';
    }
}
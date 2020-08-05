<?php

namespace CarroPublic\CarroMessenger\Listeners;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use CarroPublic\CarroMessenger\Events\MessageWasSent;

class UpdateMessageSentModel
{
    /**
     * Handling MessagWasSend event
     *
     * @param MessageWasSent $event
     */
    public function handle(MessageWasSent $event)
    {
        $event->sentModel->update(
            [
                'message_id'    => $event->messageId
            ]
        );
    }
}
# Message Bird

Message bird provides programable webhooks to handle message statuses and incoming message

### Accepting incoming message

You can see webhooks list and can create and delete webhooks with this command

``` bash
php artisan whatsapp:message-bird:webhooks
```

You need to careat a webhook events with `crated` to handle incoming message

You can see webhooks list and can create and delete..

### Sending outgoing message

Here is how to use carromessenger with message bird..

```php
$data = [
    'to'            => '+959XXXXXXXX',
    'message'       => 'Testing',
    'service'       => 'messagebird',
    'channel'       => 'whatsapp',
    'image_url'     => 'www.example.com/example.jpg', // if you want to send images (nullable)
    'report_url'    => 'https://example.io/api/webhooks/messagebird/report', // to accept report related with outgoing messages
    'model'         => $yourModel, // needed only if you want to listen MessageWasSent event and want to do update model or something like that
];

CarroMessenger::sendMessage($data);
```

And you can also send whatsapp template messages..

Please check below codes.

```php
$data = [
    'template_name'     => $messageBirdTemplateName, // template name your whatsapp message created on message bird template manager
    'to'                => '+959XXXXXXXX',
    'params'            => $params, // array of your template message parameters
    'model'             => $yourModel,
    'report_url'        => 'https://example.io/api/webhooks/messagebird/report',
    'language_code'     => 'en',
];

CarroMessenger::sendWhatsAppTemplateSMSViaMsgBird($data);
```


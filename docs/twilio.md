# Twilio

### Sending outgoing message

Here is how to use carromessenger with message bird..

```php
$data = [
    'to'            => '+959XXXXXXXX', // phone number you would like to send
    'message'       => 'Testing',
    'service'       => 'twilio',
    'channel'       => 'whatsapp',
    'from'          => '+65XXXXXX', // your whatsapp phone number from twilio
    'image_url'     => 'www.example.com/example.jpg', // if you want to send images (nullable)
    'model'         => $yourModel, // needed only if you want to listen MessageWasSent event and want to do update model or something like that
];

CarroMessenger::sendMessage($data);
```

With twilio you can send tempalate messages as normal message.
It's same as sending normal message.

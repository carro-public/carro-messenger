# Telerivet

### Sending outgoing message

```php
$data = [
    'service'   => 'telerivet',
    'channel'   => 'sms2way',
    'to'        => '+959XXXXX',
    'message'   => 'Testing',
    'model'     => $yourModel,
];

CarroMessenger::sendMessage($data);
```

### Handling incoming messages

Telerivet doesn't support programable webhooks

You need to add in telerivet's dashborad manually.


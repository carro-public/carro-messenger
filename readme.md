# CarroMessenger

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

You can send WhatsApp messages and template message using [Message bird](https://www.messagebird.com) and [Twilio](https://www.twilio.com/),
SMS messages using [Twilio](https://www.twilio.com/) and [Telerivet](https://telerivet.com/) using this package. You can create webhooks for programmable webhooks of [Toky](https://toky.co/en) to handle incoming phone calls.

You need to prepare an array with service and channel (WhatsApp, SMS, etc) which you would like to use. Then call the `sendMessage()`. You can see an example in the following

```php
$data = [
    'to'        => '+959XXXXXXXX',
    'message'   => 'Testing',
    'service'   => 'twilio',
    'channel'   => 'whatsapp',
    'image_url' => 'www.example.com/example.jpg'
];
CarroMessenger::sendMessage($data);
```

## Installation

Via Composer

``` bash
$ composer require carropublic/carromessenger
```

## Usage

You need to set `.env` values for each service that you would like to use.

```
#For Message Bird
MESSAGE_BIRD_ACCESS_KEY                     = message_bird_key
MESSAGE_BIRD_WHATS_APP_CHANNEL_ID           = whatsapp_channel_from_message_bird
MESSAGE_BIRD_WHATSAPP_TEMPLATE_NAMESPACE    = whatsapp_tempalate_namespace (only needed it you would like to send template message)
MESSAGE_BIRD_WHATSAPP_REPORT_URL            = whatsapp_message_status_report_url (optional)
MESSAGE_BIRD_WHATSAPP_PHONE                 = whatsapp_message_bird_phone // (optional)

#For Twilio
TWILIO_ACCOUNT_SID              = twilio_account_sid
TWILIO_AUTH_TOKEN               = twilio_auth_token

#For Telerivet
TELERIVET_API_KEY               = telerivet_api_key
TELERIVET_PROJECT_ID            = telerivet_project_id

#For Toky
TOKY_APP_KEY                    = toky_app_key
TOKY_APP_URL                    = toky_app_url
```

Then, create an array to send out the message like the following. It's as simple as that.
``` php
$data = [
    'to'        => '+959XXXXXXXX',
    'message'   => 'Testing',
    'service'   => 'twilio',
    'channel'   => 'whatsapp',
    'image_url' => 'www.example.com/example.jpg'
];
CarroMessenger::sendMessage($data);
```

The following services support for the error message and status report with programmable webhooks. You can read about each individual one by clicking the following list.

- [MessageBird](docs/message-bird.md)
- [Twilio](docs/twilio.md)
- [Telerivet](docs/telerivet.md)
- [Toky](docs/toky.md)

For other services that are not listed above, you may need to rely on events.

### Events

We are extending the Laravel's Notification to sent messages. Therefore, you can't see the success/error with an immediate response. In order to resolve this, we added an event name call `MessageWasSent`. The package will be sent to an event every time you sent the messages. But, you have to set `EVENT_IS_CALLED` as `true` in your `.env`.  Then, you create a listener for that event and handle the result as your needs.
You can also use [Laravel's Notification Event](https://laravel.com/docs/7.x/notifications#notification-events) as well.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/carropublic/carromessenger.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/carropublic/carromessenger.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/carropublic/carromessenger/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/carropublic/carromessenger
[link-downloads]: https://packagist.org/packages/carropublic/carromessenger
[link-travis]: https://travis-ci.org/carropublic/carromessenger
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/carropublic
[link-contributors]: ../../contributors

# CarroMessenger

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

This package can send whatsapp messages and whatsapp tmeplate message using [Message bird](https://www.messagebird.com) and [Twilio](https://www.twilio.com/),
SMS messages using [Twilio](https://www.twilio.com/) and [Telerivet](https://telerivet.com/).
And also can create webhooks for [Toky](https://toky.co/en) phone calls to handle incoming phone calls.

To be used easier, we just need to prepare an array and set service and channel (WhatsApp, SMS, etc) which you would like to use.
Then, pass it, our package will acutomatically send message as you set correctly.

# Motivation

We have to use multiple services for multiple message types. Then, handling each service with related package would be easier to maintain for us.

## Installation

Via Composer

``` bash
$ composer require carropublic/carromessenger
```

## Usage

Too easy to use our combo messenger package.. You just need to create a array and pass it.

We will handle all the things.

Here is sample array

``` bash
$data = [
    'to'        => '+959XXXXXXXX',
    'message'   => 'Testing',
    'service'   => 'twilio',
    'channel'   => 'whatsapp',
    'image_url' => 'www.example.com/example.jpg'
];

CarroMessenger::sendMessage($data);
```

Some services support for message error and status reporting with programable webhooks.
You can read more details for each service..

- [MessageBird](docs/message-bird.md)

- [Twilio](docs/twilio.md)

- [Telerivet](docs/telerivet.md)

- [Toky](docs/toky.md)


### MessageWasSent event

You would see `model` in some of our samples..
Read [here](docs/event-listener.md) why we crated it and how can you use it..


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

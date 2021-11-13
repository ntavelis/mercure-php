# Mercure-php

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
![CI](https://github.com/ntavelis/mercure-php/workflows/CI/badge.svg)
[![codecov](https://codecov.io/gh/ntavelis/mercure-php/branch/master/graph/badge.svg)](https://codecov.io/gh/ntavelis/mercure-php)
[![Total Downloads][ico-downloads]][link-downloads]

This package publishes notifications to the mercure hub from your php application. These messages can be later consumed from the clients(web-browsers or mobile apps) to provide real-time updates to your application. All of this is possible due to the Mercure protocol, you can read more about the protocol [here](https://github.com/dunglas/mercure/blob/master/spec/mercure.md).

Shoutout to [dunglas](https://github.com/dunglas) for his work in the [mercure project](https://github.com/dunglas/mercure/blob/master/spec/mercure.md).

## Install

Install the package via Composer

``` bash
$ composer require ntavelis/mercure-php
```

## Mercure Hub installation

The mercure hub which is a binary written in GO Lang, should be up and running, in order to accept the messages from the php application.

Please refer to the [official documentation](https://mercure.rocks/docs/hub/install) on how to Install the hub:
 
## Sending a public notification

We need to publish messages from our php server to the mercure hub and then consume them in our client, in this example in a browser via javascript.

### PHP code 

The below example is a controller, in symfony framework:
```php
<?php

namespace App\Controller;

use Ntavelis\Mercure\Messages\Notification;
use Ntavelis\Mercure\Providers\PublisherTokenProvider;
use Ntavelis\Mercure\Publisher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublishController extends AbstractController
{
    /**
     * @Route("/publish", name="publish")
     */
    public function index()
    {
        $notification = new Notification(['http://localhost/books/2'], ['data' => 'new public event']);
        
        $publisher = new Publisher(
            'http://localhost:3000/.well-known/mercure',
            new PublisherTokenProvider('aVerySecretKey'),
            new Psr18Client()
        );

        $publisher->send($notification);

        return new JsonResponse(['success']);
    }
}
``` 

Note: When we initialize the publisher we need to pass a PSR-18 compliant client, in our example we use the symfony/http-client. This package does not provide a client you need to initialize and pass one to the publisher yourself. e.g. To provide the symfony http-client you need to install it first via composer: 
```bash
composer require symfony/http-client
```

Tip: Instead of manually building the classes, you can achieve the same result by using the [fluent API](docs/Builders.md).

#### Notification class
The first argument of the Ntavelis\Mercure\Messages\Notification is an array of topics, you want to publish a notification for. The topics can be any string that makes sense for you, e.g. 'orders', 'clients', 'notes', 'http://localhost/books/2' etc. The second argument is the array of data you want to pass to your client, this array will be json encoded and it will be received from the clients, which can then act upon that received data.

#### Publisher class
This is the class that it actually sends the notification to the mercure hub, it expects 3 arguments upon instantiation. The mercure hub url, a class that implements the Ntavelis\Mercure\Contracts\TokenProviderInterface (you can use the one from the package or provide your own) and lastly as mentioned above an instance of a PSR-18 compatible client.

### Client-side Javascript code

In order to consume the above public message, our client side code will look like this:

```javascript
// The subscriber subscribes to updates for any topic matching http://localhost/books/{id}
const url = new window.URL('http://localhost:3000/.well-known/mercure');
url.searchParams.append('topic', 'http://localhost/books/{id}');

const eventSource = new EventSource(url.toString());

// The callback will be called every time an update is published
eventSource.onmessage = e => {
    console.log(JSON.parse(e.data));// do something with the payload
};
```

Note: We used a wildcard for the id, so we will receive a notification for books with any given {id}.

The above example uses native js code, without any library. Please check the [EventSource](https://developer.mozilla.org/en-US/docs/Web/API/EventSource) documentation for more information. 

Optionally we can specify a specific type for our topic and listen only for that type in our frontend, more info [here](docs/EventTypes.md)
## Private messages
Unlike public messages, private messages are not meant to be consumed from everybody. Private messages are messages that are meant to be consumed from a specific list of targets. For example you can publish messages for a specific user, or a list of users. Another example would be to publish messages for the admin role, so every user that is admin would receive them on the client and act upon them.

To publish and consume private messages we need 3 things:
1. To publish a private notification from our php server code.
2. Provide an endpoint to generate the token for our frontend.
3. Make a request from the client to the backend to get a token that proves we are able to receive the private message and subscribe to events using the token we received.

### PHP code (Step 1)
From our php server code, we now have to use the `Ntavelis\Mercure\Messages\PrivateNotification` class, which receives the same arguments as the Notification class, but marks the notification as private.

```php
<?php

namespace App\Controller;

use Ntavelis\Mercure\Messages\PrivateNotification;
use Ntavelis\Mercure\Providers\PublisherTokenProvider;
use Ntavelis\Mercure\Publisher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublishController extends AbstractController
{
    /**
     * @Route("/publish", name="publish")
     */
    public function index()
    {
        $notification = new PrivateNotification(
            ['http://localhost/author/ntavelis/books/155'],
            ['data' => 'new private event']
        );

        $publisher = new Publisher(
            'http://localhost:3000/.well-known/mercure',
            new PublisherTokenProvider('aVerySecretKey'),
            new Psr18Client()
        );

        $publisher->send($notification);

        return new JsonResponse(['success']);
    }
}
```

That's it, we published a private message that is meant only for the user `ntavelis` as the topic specified `http://localhost/author/ntavelis/books/155`. Perhaps he is the author of the book in our app and we would like to send a client notification to update his private dashboard.

Tip: Instead of manually building the classes, you can achieve the same result by using the [fluent API](docs/Builders.md).
### Provide the endpoint that will generate the token for the client (Step 2)

To consume the messages in our javascript, we need to provide a valid token when we subscribe to the hub to prove that we are the user `ntavelis` this private notification is meant for. To do this we can make an ajax request to a php endpoint to receive the token.
This package will generate the token for us, we only need to provide an endpoint that the client can call to receive the token.

This is the php code, that generates the token for the client (the subscriber):

```php
<?php

namespace App\Controller;

use Ntavelis\Mercure\Providers\SubscriberTokenProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscribeController extends AbstractController
{
    /**
     * @Route("/subscribe", name="subscribe")
     */
    public function index(Request $request)
    {
        $content = $request->getContent();

        $contentArray = json_decode($content, true);
        $topic = $contentArray['topic'];

        // TODO authorize the request
        $provider = new SubscriberTokenProvider('aVerySecretKey');
        $token = $provider->getToken([$topic]);

        return new JsonResponse(['token' => $token]);
    }
}
```
In the above example we used the `Ntavelis\Mercure\Providers\SubscriberTokenProvider` to get the token valid for a particular topic.

Note: To authorize the request is up to you, you should check that the request is valid and it can receive private notifications for this topic.

### Obtain the token in the client and subscribe to events using that token (Step 3)

Final step that puts it all together, from our client-side code we obtain the token and we subscribe to the events from the hub using this token.

Note: we are gonna use a polyfill library in this example to pass the authorization header to the hub, because it is not natively supported from the EventSource.

```javascript
// use a polyfill library
import { EventSourcePolyfill } from 'event-source-polyfill';

// Make a post request to the server to obtain the token for the topic we want to receive notifications for
const token = fetch('/subscribe', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({topic: 'http://localhost/author/ntavelis/books/155'}), // send the currently authenticated user
}).then(response => response.json())
    .then((json) => json.token);

// When we have the token subscribe to the EventSource by passing the token
token.then((token) => {
    const url = new window.URL('http://localhost:3000/.well-known/mercure');
    url.searchParams.append('topic', 'http://localhost/author/ntavelis/books/155');
    // Authorization header
    const eventSourceInitDict = {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    };
    const es = new EventSourcePolyfill(url.toString(), eventSourceInitDict);
    es.onmessage = e => {
        console.log(JSON.parse(e.data));
    };
});
```
Keep in mind that you can also use cookie based authentication to connect to the hub, you can read more about it [here](https://symfony.com/doc/current/mercure.html#authorization).

## Extra

If you want to configure notifications for a specific type, consult the documentation [here](docs/EventTypes.md). 

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email davelis89@gmail.com instead of using the issue tracker.

## Credits

- [Athanasios Ntavelis][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/ntavelis/mercure-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/ntavelis/mercure-php/master.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/ntavelis/mercure-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ntavelis/mercure-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/ntavelis/mercure-php
[link-travis]: https://travis-ci.org/ntavelis/mercure-php
[link-downloads]: https://packagist.org/packages/ntavelis/mercure-php
[link-author]: https://github.com/ntavelis
[link-contributors]: ../../contributors

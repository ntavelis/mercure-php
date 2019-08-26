# Mercure-php

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This package publishes notifications to the mercure hub from your php application. This messages can be later consumed from the clients(web-browsers or mobile apps) to provide real-time updates to your application. All of this is possible due to the Mercure protocol, you can read more about the protocol [here](https://github.com/dunglas/mercure/blob/master/spec/mercure.md).

Shoutout to [dunglas](https://github.com/dunglas) and his work in the [mercure project](https://github.com/dunglas/mercure/blob/master/spec/mercure.md).

## Install

Install the package via Composer

``` bash
$ composer require ntavelis/mercure-php
```

## Mercure Hub installation

This a a binary written in GO lang which should be up and running, in order to accept the messages from the php application.

We suggest to install it locally by using the official docker image:

``` bash
docker run \
    -e JWT_KEY='aVerySecretKey' -e DEMO=1 -e ALLOW_ANONYMOUS=1 -e PUBLISH_ALLOWED_ORIGINS='http://localhost' \
    -p 3000:80 \
    dunglas/mercure
``` 

Or by using a docker-compose configuration:

``` yaml
version: '3.1'

services:
    mercure:
        image: dunglas/mercure
        environment:
            - JWT_KEY=aVerySecretKey
            - ALLOW_ANONYMOUS=1
            - CORS_ALLOWED_ORIGINS=*
            - PUBLISH_ALLOWED_ORIGINS=http://localhost
            - DEMO=1
        ports:
            - 3000:80
        labels:
            - traefik.frontend.rule=Host:localhost
``` 

Alternatively you can download and run the executable, choose the correct executable for your operating system from [here](https://github.com/dunglas/mercure/releases) and run:

```bash
JWT_KEY='aVerySecretKey' ADDR=':3000' DEMO=1 ALLOW_ANONYMOUS=1 CORS_ALLOWED_ORIGINS=* PUBLISH_ALLOWED_ORIGINS='http://localhost:3000' ./mercure
``` 
 
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
            'http://localhost:3000/hub',
            new PublisherTokenProvider('aVerySecretKey'),
            new Psr18Client()
        );

        $publisher->send($notification);

        return new JsonResponse(['success']);
    }
}
``` 

Note: We need to initialize the publisher and pass him a PSR-18 compliant client, in our example we use the symfony/http-client. This package does not provide a client you need to initialize and pass one to the publisher yourself. e.g. To provide the symfony http-client you need to install it first via composer: 
```bash
composer require symfony/http-client
```

#### Notification class
The first argument of the `Ntavelis\Mercure\Messages\Notification` is an array of topics, you want to publish a notification for. The topics can be any string that makes sense for you, e.g. 'orders', 'clients', 'notes', 'http://localhost/books/2' etc.
The second argument is the array of data you want to pass to your client, this array will be json encoded and it will be received from the clients, which can then act upon that received data.  

#### Publisher class
This is the class that it actually sends the notification to the mercure hub, it expects 3 arguments upon instantiation. The mercure hub url, a class that implements the `Ntavelis\Mercure\Contracts\TokenProviderInterface` (you can use the one from the package or provide your own) and lastly as mentioned above an instance of a PSR-18 compatible client.

### Client-side Javascript code

In order to consume the above public message, our client side code will look like this:

```javascript
// The subscriber subscribes to updates for any topic matching http://localhost/books/{id}
const url = new window.URL('http://localhost:3000/hub');
url.searchParams.append('topic', 'http://localhost/books/{id}');

const eventSource = new EventSource(url.toString());

// The callback will be called every time an update is published
eventSource.onmessage = e => {
    console.log(JSON.parse(e.data));// do something with the payload
};
```

Note: We used a wildcard for the id, so we will receive a notification for books with any given {id}.

The above example uses native js code, without any library. Please check the [EventSource](https://developer.mozilla.org/en-US/docs/Web/API/EventSource) documentation for more information. 

## Private messages
Unlike public messages, private messages are not meant to be consumed from everybody. Private messages are messages that are meant to be consumed from a specific list of targets. For example you can publish messages for a specific user, or a list of users. Another example would be to publish messages for the admin role, so every user that is admin would receive them on the client and act upon them.

To publish and consume private messages we need 3 things:
1. To publish a private notification from our php server code.
2. Provide an endpoint to generate the token for our frontend.
3. Make a request from the client to the backend to get a token that proves we are able to receive the private message and subscribe to events using the token we received.

### PHP code (Step 1)
From our php server code, we now have to use the `Ntavelis\Mercure\Messages\PrivateNotification` class, which receives the same arguments as the Notification class with the addition of a third argument, an array of targets this message is meant for.

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
            ['http://localhost/books/155'],
            ['data' => 'new private event'],
            ['ntavelis']
        );

        $publisher = new Publisher(
            'http://localhost:3000/hub',
            new PublisherTokenProvider('aVerySecretKey'),
            new Psr18Client()
        );

        $publisher->send($notification);

        return new JsonResponse(['success']);
    }
}
```

That's it, we published a private message that is meant only for the user `ntavelis` and is related to the topic `http://localhost/books/155`. Perhaps he is the author of the book in our app and we would like to send a client notification to update his private dashboard.

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
        $username = $contentArray['username'];
        
        // TODO authorize the request, by checking that this request actually comes from the user `ntavelis`
        $provider = new SubscriberTokenProvider('aVerySecretKey');
        $token = $provider->getToken([$username]); // Get token for user ntavelis

        return new JsonResponse(['token' => $token]);
    }
}
```
In the above example we used the `Ntavelis\Mercure\Providers\SubscriberTokenProvider` to get the token for the user `ntavelis`.

Note: To authorize the request is up to you, you should check that the request is valid and it can receive private notifications for this target, in our case this specific user.

### Obtain the token in the client and subscribe to events using that token (Step 3)

Final step that puts it all together, from our client-side code we obtain the token and we subscribe to the evetns from the hub using this token.

Note: we are gonna use a polyfill library in this example to pass the authorization header to the hub, because it is not natively supported from the EventSource.

```javascript
// use a polyfill library
import EventSource from 'eventsource'

// Make a post request to the server to obtain the token for the user `ntavelis`
const token = fetch('/subscribe', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({username: 'ntavelis'}), // send the currently authenticated user
}).then(response => response.json())
    .then((json) => json.token);

// When we have the token subscribe to the EventSource by passing the toke
token.then((token) => {
    const url = new window.URL('http://localhost:3000/hub');
    url.searchParams.append('topic', 'http://localhost/books/155');
    // Authorization header
    const eventSourceInitDict = {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    };
    const es = new EventSource(url.toString(), eventSourceInitDict);
    es.onmessage = e => {
        console.log(JSON.parse(e.data));
    };
});
```
Keep in mind that you can also use cookie based authentication to connect to the hub, you can read more about it [here](https://symfony.com/doc/current/mercure.html#authorization).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer unit-tests
```

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
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/ntavelis/mercure-php.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/ntavelis/mercure-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ntavelis/mercure-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/ntavelis/mercure-php
[link-travis]: https://travis-ci.org/ntavelis/mercure-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/ntavelis/mercure-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/ntavelis/mercure-php
[link-downloads]: https://packagist.org/packages/ntavelis/mercure-php
[link-author]: https://github.com/ntavelis
[link-contributors]: ../../contributors

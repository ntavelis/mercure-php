## Builder classes

We can also use the builder classes to build the notification objects and the publisher with a fluent API.

The below examples achieve the same result as in publishing public and private messages. 

### Public notification example using the fluent API

```php
<?php

namespace App\Controller;

use Ntavelis\Mercure\Builder\NotificationBuilder;
use Ntavelis\Mercure\Builder\PublisherBuilder;
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
        $publisher = (new PublisherBuilder())
            ->mercureHubUrl('http://localhost:3000/.well-known/mercure')
            ->key('aVerySecretKey')
            ->psr18Client(new Psr18Client())
            ->get();
        
        $notification = (new NotificationBuilder())
            ->topic('http://localhost/books/2')
//            ->topic('anotherTopic')
            ->withData(['data' => 'new public event'])
            ->inPublic();

        $publisher->send($notification);

        return new JsonResponse(['success']);
    }
}
```

### Private notification example using the fluent API

```php
<?php

namespace App\Controller;

use Ntavelis\Mercure\Builder\NotificationBuilder;
use Ntavelis\Mercure\Builder\PublisherBuilder;
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
        $publisher = (new PublisherBuilder())
            ->mercureHubUrl('http://localhost:3000/.well-known/mercure')
            ->key('aVerySecretKey')
            ->psr18Client(new Psr18Client())
            ->get();
        
        $notification = (new NotificationBuilder())
            ->topic('http://localhost/books/155')
//            ->topic('anotherTopic')
            ->withData(['data' => 'new private event'])
            ->inPrivateTo('ntavelis');
//            ->inPrivateTo('ntavelis', 'anotherUser'); // we can pass as many targets we want

        $publisher->send($notification);

        return new JsonResponse(['success']);
    }
}
```
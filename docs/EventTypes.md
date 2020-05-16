## Event Types

We can optionally specify a type when we create a notification class, by doing this we will publish a notification for a given topic with a specific type. 

This will allow us to differentiate the kind of events we want to respond for a specific topic. For example, we can have for a book topic for event types such as `invoice` or `comment`.

It is easier to demonstrate with an example:

### PHP code
```php
<?php

namespace App\Controller;

use Ntavelis\Mercure\Builder\NotificationBuilder;
use Ntavelis\Mercure\Builder\PublisherBuilder;
use Ntavelis\Mercure\Config\ConfigStamp;
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
            ->withConfig((new ConfigStamp)->setType('invoice')) // Configure the message to be of type invoice
            ->withData(['data' => 'new public event'])
            ->inPublic();

        $publisher->send($notification);

        return new JsonResponse(['success']);
    }
}
```

### Javascript code

```javascript
// The subscriber subscribes to updates for any topic matching http://localhost/books/{id}
const url = new window.URL('http://localhost:3000/.well-known/mercure');
// We are interested for the topic http://localhost/books/2
url.searchParams.append('topic', 'http://localhost/books/2');

const eventSource = new EventSource(url.toString());

// we subscribe to event type 'invoice' for this particular topic
// we will ignore other type of events
// notice we do not use the `eventSource.onmessage` as we did we we did not specify specific type
eventSource.addEventListener('invoice', function(e) {
    console.log(JSON.parse(e.data))
})
```


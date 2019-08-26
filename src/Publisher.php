<?php

declare(strict_types=1);

namespace Ntavelis\Mercure;

use Ntavelis\Mercure\Contracts\NotificationInterface;
use Ntavelis\Mercure\Contracts\PublisherInterface;
use Ntavelis\Mercure\Contracts\TokenProviderInterface;
use Ntavelis\Mercure\Exceptions\UnableToSendNotificationException;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Stream;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

class Publisher implements PublisherInterface
{
    private $mercureHubUrl;
    private $tokenProvider;
    private $client;

    public function __construct(
        string $mercureHubUrl,
        TokenProviderInterface $tokenProvider,
        ClientInterface $client
    ) {
        $this->mercureHubUrl = $mercureHubUrl;
        $this->tokenProvider = $tokenProvider;
        $this->client = $client;
    }

    /**
     * @throws UnableToSendNotificationException
     */
    public function send(NotificationInterface $notification): string
    {
        $request = new Request('POST', $this->mercureHubUrl, [], $this->getRequestBody($notification));
        $request = $this->addHeadersToRequest($request, $notification);

        try {
            $response = $this->client->sendRequest($request);
            if ($response->getStatusCode() !== 200) {
                throw new UnableToSendNotificationException($response->getBody()->getContents());
            }

            return $response->getBody()->getContents();
        } catch (ClientExceptionInterface $e) {
            throw new UnableToSendNotificationException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    private function addHeadersToRequest(
        RequestInterface $request,
        NotificationInterface $notification
    ): RequestInterface {
        $request = $request->withAddedHeader('Content-type', 'application/x-www-form-urlencoded');
        $token = $this->tokenProvider->getToken($notification->getTokenData());
        return $request->withAddedHeader('Authorization', 'Bearer ' . $token);
    }

    private function getRequestBody(NotificationInterface $notification): StreamInterface
    {
        return Stream::create((string)new QueryBuilder($notification));
    }
}

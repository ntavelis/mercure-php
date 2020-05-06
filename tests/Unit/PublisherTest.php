<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit;

use Ntavelis\Mercure\Exceptions\UnableToSendNotificationException;
use Ntavelis\Mercure\Messages\Notification;
use Ntavelis\Mercure\Messages\PrivateNotification;
use Ntavelis\Mercure\Providers\PublisherTokenProvider;
use Ntavelis\Mercure\Publisher;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class PublisherTest extends TestCase
{
    /** @test */
    public function itCanPublishANotificationToTheMercureHub(): void
    {
        $notification = new Notification(['topic'], ['data']);

        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())->method('sendRequest')
            ->willReturn(new Response(200, [], Stream::create('1b6bcc96-c54e-4776-884c-5812c9ea8a71')));
        $publisher = new Publisher(
            'http://hub-url.com',
            new PublisherTokenProvider('token'),
            $client
        );

        $publisher->send($notification);
    }

    /** @test */
    public function itCanPublishAPrivateNotificationToTheMercureHub(): void
    {
        $notification = new PrivateNotification(['topic'], ['data'], ['ntavelis']);

        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())->method('sendRequest')
            ->willReturn(new Response(200, [], Stream::create('1b6bcc96-c54e-4776-884c-5812c9ea8a71')));
        $publisher = new Publisher(
            'http://hub-url.com',
            new PublisherTokenProvider('token'),
            $client
        );

        $publisher->send($notification);
    }

    /** @test */
    public function ifWeCanNotPublishToTheHubWeThrowAnException(): void
    {
        $this->expectException(UnableToSendNotificationException::class);
        $notification = new Notification(['topic'], ['data']);

        $client = $this->createMock(ClientInterface::class);
        $client
            ->expects($this->once())
            ->method('sendRequest')
            ->willThrowException($this->createMock(ClientExceptionInterface::class));
        $publisher = new Publisher(
            'http://hub-url.com',
            new PublisherTokenProvider('token'),
            $client
        );

        $publisher->send($notification);
    }

    /** @test */
    public function ifWeGetAResponseWithStatusOtherThan200WeThrowAnException(): void
    {
        $this->expectException(UnableToSendNotificationException::class);
        $notification = new Notification(['topic'], ['data']);

        $client = $this->createMock(ClientInterface::class);
        $client
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn(new Response(401, [], Stream::create("Unauthorized\n")));
        $publisher = new Publisher(
            'http://hub-url.com',
            new PublisherTokenProvider('token'),
            $client
        );

        $publisher->send($notification);
    }
}

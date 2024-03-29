<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Functional;

use Ntavelis\Mercure\Config\ConfigStamp;
use Ntavelis\Mercure\Exceptions\UnableToSendNotificationException;
use Ntavelis\Mercure\Messages\Notification;
use Ntavelis\Mercure\Messages\PrivateNotification;
use Ntavelis\Mercure\Providers\PublisherTokenProvider;
use Ntavelis\Mercure\Publisher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Psr18Client;

class PublisherTest extends TestCase
{
    /** @test */
    public function itCanSuccessfullyPublishAPublicMessage(): void
    {
        $notification = new Notification(['http://localhost:3000/demo/books/1.jsonld'], ['key' => 'updated value']);

        $psr18Client = new Psr18Client();
        $publisher = new Publisher(
            'http://mercure:80/.well-known/mercure',
            new PublisherTokenProvider('aVerySecretKey'),
            $psr18Client
        );

        $id = $publisher->send($notification);

        $this->assertNotEmpty($id);
    }

    /** @test */
    public function itCanSuccessfullyPublishAPrivateMessage(): void
    {
        $notification = new PrivateNotification(
            ['http://localhost:3000/demo/books/1.jsonld'],
            ['key' => 'updated value'],
        );

        $psr18Client = new Psr18Client();
        $publisher = new Publisher(
            'http://mercure:80/.well-known/mercure',
            new PublisherTokenProvider('aVerySecretKey'),
            $psr18Client
        );

        $id = $publisher->send($notification);

        $this->assertNotEmpty($id);
    }

    /** @test */
    public function ifWeGetAStatusNotEqualTo200WeThrowAnException(): void
    {
        $this->expectException(UnableToSendNotificationException::class);
        $this->expectExceptionMessage("Unauthorized\n");
        $notification = new PrivateNotification(
            ['http://localhost:3000/demo/books/1.jsonld'],
            ['key' => 'updated value'],
        );

        $psr18Client = new Psr18Client();
        $publisher = new Publisher(
            'http://mercure:80/.well-known/mercure',
            new PublisherTokenProvider('wrong_key'),
            $psr18Client
        );

        $publisher->send($notification);
    }

    /** @test */
    public function itCanSuccessfullyPublishAPublicMessageWithConfigValues(): void
    {
        $notification = new Notification(['http://localhost:3000/demo/books/1.jsonld'], ['key' => 'updated value']);
        $configStamp = new ConfigStamp('invoice', '1234', 30);
        $notification->withConfig($configStamp);

        $psr18Client = new Psr18Client();
        $publisher = new Publisher(
            'http://mercure:80/.well-known/mercure',
            new PublisherTokenProvider('aVerySecretKey'),
            $psr18Client
        );

        $id = $publisher->send($notification);

        $this->assertSame('1234', $id);
    }
}

<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Builder;

use Ntavelis\Mercure\Builder\NotificationBuilder;
use Ntavelis\Mercure\Config\ConfigStamp;
use Ntavelis\Mercure\Messages\Notification;
use Ntavelis\Mercure\Messages\PrivateNotification;
use PHPUnit\Framework\TestCase;

class NotificationBuilderTest extends TestCase
{
    /** @test */
    public function itCanBuildANotificationFluently(): void
    {
        $notification = (new NotificationBuilder())
            ->topic('aTopic')
            ->withData(['data' => 'Public Event'])
            ->inPublic();

        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertSame(['aTopic'], $notification->getTopics());
        $this->assertSame(['data' => 'Public Event'], $notification->getData());
    }

    /** @test */
    public function itCanBuildAPrivateNotificationFluently(): void
    {
        $notification = (new NotificationBuilder())
            ->topic('aTopic')
            ->withData(['data' => 'Public Event'])
            ->inPrivateTo('ntavelis');

        $this->assertInstanceOf(PrivateNotification::class, $notification);
        $this->assertSame(['aTopic'], $notification->getTopics());
        $this->assertSame(['data' => 'Public Event'], $notification->getData());
        $this->assertSame(['ntavelis'], $notification->getTargets());
    }

    /** @test */
    public function itCanAcceptMultipleTopics(): void
    {
        $notification = (new NotificationBuilder())
            ->topic('aTopic')
            ->topic('anotherTopic')
            ->withData(['data' => 'Public Event'])
            ->inPublic();

        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertSame(['aTopic', 'anotherTopic'], $notification->getTopics());
        $this->assertSame(['data' => 'Public Event'], $notification->getData());
    }

    /** @test */
    public function itCanAcceptMultipleTargets(): void
    {
        $notification = (new NotificationBuilder())
            ->topic('aTopic')
            ->withData(['data' => 'Public Event'])
            ->inPrivateTo('ntavelis', 'anotherOne');

        $this->assertInstanceOf(PrivateNotification::class, $notification);
        $this->assertSame(['aTopic'], $notification->getTopics());
        $this->assertSame(['data' => 'Public Event'], $notification->getData());
        $this->assertSame(['ntavelis', 'anotherOne'], $notification->getTargets());
    }

    /** @test */
    public function itCanBuildANotificationFluentlyWithConfigValues(): void
    {
        $configStamp = (new ConfigStamp)->setType('notification');
        $notification = (new NotificationBuilder())
            ->topic('aTopic')
            ->withData(['data' => 'Public Event'])
            ->withConfig($configStamp)
            ->inPublic();

        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertSame($configStamp, $notification->getConfigStamp());
    }

    /** @test */
    public function itCanBuildANotificationFluentlyWithConfigValuesAndForPrivateMessages(): void
    {
        $configStamp = (new ConfigStamp)->setType('notification');
        $notification = (new NotificationBuilder())
            ->topic('aTopic')
            ->withData(['data' => 'Public Event'])
            ->withConfig($configStamp)
            ->inPrivateTo('ntavelis', 'anotherOne');

        $this->assertInstanceOf(PrivateNotification::class, $notification);
        $this->assertSame($configStamp, $notification->getConfigStamp());
    }
}

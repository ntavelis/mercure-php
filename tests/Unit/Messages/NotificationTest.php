<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Messages;

use Ntavelis\Mercure\Config\ConfigStamp;
use Ntavelis\Mercure\Messages\Notification;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    /** @test */
    public function itHoldsTheInformationThatWillBeSendToMercureHub(): void
    {
        $notification = new Notification(['topics'], ['data']);

        $this->assertSame(['topics'], $notification->getTopics());
        $this->assertSame(['data'], $notification->getData());
    }

    /** @test */
    public function itCanReturnAnArrayRepresentationOfItSelf(): void
    {
        $notification = new Notification(['topics'], ['data-that-will-get-json-encoded']);

        $this->assertSame([
            'topic' => ['topics'],
            'data' => '["data-that-will-get-json-encoded"]',
            'id' => null,
            'type' => null,
            'retry' => null,
        ], $notification->toArray());
    }

    /** @test */
    public function itCanBeConfiguredThroughAConfigStampClass(): void
    {
        $notification = new Notification(['topics'], ['data']);
        $configStamp = (new ConfigStamp())
            ->setType('notification')
            ->setId('12314')
            ->setRetry(30);
        $notification->withConfig($configStamp);

        $this->assertSame([
            'topic' => ['topics',],
            'data' => '["data"]',
            'id' => '12314',
            'type' => 'notification',
            'retry' => 30,
        ], $notification->toArray());
    }
}

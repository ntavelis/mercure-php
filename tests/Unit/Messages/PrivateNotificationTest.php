<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Messages;

use Ntavelis\Mercure\Config\ConfigStamp;
use Ntavelis\Mercure\Messages\PrivateNotification;
use PHPUnit\Framework\TestCase;

class PrivateNotificationTest extends TestCase
{
    /** @test */
    public function itHoldTheInformationThatWillBeSendToTheDockerHub(): void
    {
        $privateNotification = new PrivateNotification(['topics'], ['data']);

        $this->assertSame(['topics'], $privateNotification->getTopics());
        $this->assertSame(['data'], $privateNotification->getData());
    }

    /** @test */
    public function itCanReturnAJsonRepresentationOfItSelf(): void
    {
        $privateNotification = new PrivateNotification(['topics'], ['data']);

        $this->assertSame([
            'topic' => ['topics'],
            'data' => '["data"]',
            'id' => null,
            'type' => null,
            'retry' => null,
            'private' => 'on',
        ], $privateNotification->toArray());
    }

    /** @test */
    public function itCanBeConfiguredThroughAConfigStampClass(): void
    {
        $notification = new PrivateNotification(['topics'], ['data']);
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
            'private' => 'on',
        ], $notification->toArray());
    }
}

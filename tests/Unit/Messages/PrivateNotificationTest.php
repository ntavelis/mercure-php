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
        $privateNotification = new PrivateNotification(['topics'], ['data'], ['ntavelis']);

        $this->assertSame(['topics'], $privateNotification->getTopics());
        $this->assertSame(['data'], $privateNotification->getData());
        $this->assertSame(['ntavelis'], $privateNotification->getTargets());
    }

    /** @test */
    public function itCanReturnAJsonRepresentationOfItSelf(): void
    {
        $privateNotification = new PrivateNotification(['topics'], ['data'], ['ntavelis']);

        $this->assertSame([
            'topic' => ['topics'],
            'data' => '["data"]',
            'target' => ['ntavelis'],
            'id' => null,
            'type' => null,
            'retry' => null,
        ], $privateNotification->toArray());
    }

    /** @test */
    public function itCanBeConfiguredThroughAConfigStampClass(): void
    {
        $notification = new PrivateNotification(['topics'], ['data'], ['ntavelis']);
        $configStamp = (new ConfigStamp())
            ->setType('notification')
            ->setId('12314')
            ->setRetry(30);
        $notification->withConfig($configStamp);

        $this->assertSame([
            'topic' => ['topics',],
            'data' => '["data"]',
            'target' => ['ntavelis'],
            'id' => '12314',
            'type' => 'notification',
            'retry' => 30,
        ], $notification->toArray());
    }
}

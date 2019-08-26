<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Messages;

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
        ], $privateNotification->jsonSerialize());
    }
}

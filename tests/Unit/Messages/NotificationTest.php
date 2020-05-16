<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Messages;

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
        ], $notification->toArray());
    }
}

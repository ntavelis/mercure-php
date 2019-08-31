<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Providers;

use Ntavelis\Mercure\Messages\Notification;
use Ntavelis\Mercure\Providers\PublisherTokenProvider;
use PHPUnit\Framework\TestCase;

class PublisherTokenProviderTest extends TestCase
{
    /** @test */
    public function itCanReturnTheHashedToken(): void
    {
        $provider = new PublisherTokenProvider('token');

        $notification = new Notification(['topics'], ['data']);

        $hashedToken = $provider->getToken($notification->getTokenData());

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsidG9waWNzIl19fQ'
            . '.-qq-aaeWXtmKpVRJTrL1v9jUkIL43hu43lrQagvqvK0',
            $hashedToken
        );
    }
}

<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Providers;

use Ntavelis\Mercure\Providers\SubscriberTokenProvider;
use PHPUnit\Framework\TestCase;

class SubscriberTokenProviderTest extends TestCase
{
    /** @test */
    public function itCanReturnTheHashedTokenFor(): void
    {
        $provider = new SubscriberTokenProvider('token');

        $hashedToken = $provider->getToken(['ntaveli']);

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyJudGF2ZWxpIl19fQ.'
            . 'gNBs_SMPiqi_hgCkx5TJJVe6z1SWgk6h3NOcOYw7atU',
            $hashedToken
        );
    }
}

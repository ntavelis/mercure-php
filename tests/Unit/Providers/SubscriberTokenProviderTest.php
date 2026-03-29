<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Providers;

use Ntavelis\Mercure\Exceptions\InvalidSecretKeyLengthException;
use Ntavelis\Mercure\Providers\SubscriberTokenProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SubscriberTokenProviderTest extends TestCase
{
    #[Test]
    public function itThrowsExceptionWhenKeyIsShorterThan32Characters(): void
    {
        $this->expectException(InvalidSecretKeyLengthException::class);

        new SubscriberTokenProvider('tooshort');
    }

    #[Test]
    public function itCanReturnTheHashedTokenFor(): void
    {
        $provider = new SubscriberTokenProvider('aVerySecretKeyThatIsAtLeast256Bi');

        $hashedToken = $provider->getToken(['ntaveli']);

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyJudGF2ZWxpIl19fQ.'
            . 'bHHvoWzMVGUBS6KHtikX8DHat8N8M70Qib9zeV0t35k',
            $hashedToken
        );
    }
}

<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Providers;

use Ntavelis\Mercure\Exceptions\InvalidSecretKeyLengthException;
use Ntavelis\Mercure\Messages\Notification;
use Ntavelis\Mercure\Providers\PublisherTokenProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PublisherTokenProviderTest extends TestCase
{
    #[Test]
    public function itThrowsExceptionWhenKeyIsShorterThan32Characters(): void
    {
        $this->expectException(InvalidSecretKeyLengthException::class);

        new PublisherTokenProvider('tooshort');
    }

    #[Test]
    public function itCanReturnTheHashedToken(): void
    {
        $provider = new PublisherTokenProvider('aVerySecretKeyThatIsAtLeast256Bi');

        $notification = new Notification(['topics'], ['data']);

        $hashedToken = $provider->getToken($notification->getTokenData());

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsidG9waWNzIl19fQ'
            . '.bVp5aYLTlGaTBzHYtAulGrDQCIqW7xRgoEfagDWVzyQ',
            $hashedToken
        );
    }
}

<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Providers;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Ntavelis\Mercure\Contracts\TokenProviderInterface;

class SubscriberTokenProvider implements TokenProviderInterface
{
    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(array $tokenData): string
    {
        return (string)(new Builder())
            ->withClaim('mercure', ['subscribe' => $tokenData])
            ->getToken(new Sha256(), new Key($this->token));
    }
}

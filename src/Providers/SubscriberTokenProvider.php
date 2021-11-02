<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Providers;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
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
        $configuration = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($this->token));
        return $configuration->builder()
            ->withClaim('mercure', ['subscribe' => $tokenData])
            ->getToken($configuration->signer(), $configuration->signingKey())->toString();
    }
}

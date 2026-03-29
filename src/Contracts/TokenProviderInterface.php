<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Contracts;

interface TokenProviderInterface
{
    public function getToken(array $tokenData): string;
}

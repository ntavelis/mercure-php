<?php

namespace Ntavelis\Mercure\Contracts;

interface TokenProviderInterface
{
    public function getToken(array $tokenData): string;
}

<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Contracts;

interface NotificationInterface
{
    public function getTokenData(): array;

    public function toArray(): array;
}

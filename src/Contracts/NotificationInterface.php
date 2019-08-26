<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Contracts;

interface NotificationInterface extends \JsonSerializable
{
    public function getTokenData(): array;
}

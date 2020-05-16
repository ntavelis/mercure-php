<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Contracts;

use Ntavelis\Mercure\Exceptions\UnableToSendNotificationException;

interface PublisherInterface
{
    /**
     * @throws UnableToSendNotificationException
     */
    public function send(NotificationInterface $notification): string;
}

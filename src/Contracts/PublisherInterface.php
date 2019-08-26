<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Contracts;

interface PublisherInterface
{
    /**
     * Publishes a notification to the mercure hub and returns the published message's uuid
     *
     * @param NotificationInterface $notification
     * @return string
     */
    public function send(NotificationInterface $notification): string;
}

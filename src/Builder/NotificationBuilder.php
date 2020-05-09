<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Builder;

use Ntavelis\Mercure\Messages\Notification;
use Ntavelis\Mercure\Messages\PrivateNotification;

class NotificationBuilder
{
    /**
     * @var string[]
     */
    private $topics;
    /**
     * @var array
     */
    private $data;

    public function topic(string $topic): NotificationBuilder
    {
        $this->topics[] = $topic;

        return $this;
    }

    public function withData(array $data): NotificationBuilder
    {
        $this->data = $data;

        return $this;
    }

    public function inPublic(): Notification
    {
        return new Notification($this->topics, $this->data);
    }

    public function inPrivateTo(string ...$targets): PrivateNotification
    {
        return new PrivateNotification($this->topics, $this->data, $targets);
    }
}

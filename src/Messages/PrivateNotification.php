<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Messages;

use Ntavelis\Mercure\Contracts\NotificationInterface;

class PrivateNotification implements NotificationInterface
{
    private $topics;
    private $data;
    private $targets;

    public function __construct(array $topics, array $data, array $targets)
    {
        $this->topics = $topics;
        $this->data = $data;
        $this->targets = $targets;
    }

    public function getTopics(): array
    {
        return $this->topics;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getTargets(): array
    {
        return $this->targets;
    }

    public function getTokenData(): array
    {
        return array_merge($this->getTopics(), $this->getTargets());
    }

    public function toArray(): array
    {
        return [
            'topic' => $this->getTopics(),
            'data' => json_encode($this->getData()),
            'target' => $this->getTargets(),
        ];
    }
}

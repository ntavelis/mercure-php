<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Messages;

use Ntavelis\Mercure\Config\ConfigStamp;
use Ntavelis\Mercure\Contracts\NotificationInterface;

class PrivateNotification implements NotificationInterface
{
    private $topics;
    private $data;
    private $targets;
    private $configStamp;

    public function __construct(array $topics, array $data, array $targets)
    {
        $this->topics = $topics;
        $this->data = $data;
        $this->targets = $targets;
        $this->configStamp = new ConfigStamp();
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

    public function withConfig(ConfigStamp $configStamp): void
    {
        $this->configStamp = $configStamp;
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
            'id' => $this->configStamp->getId(),
            'type' => $this->configStamp->getType(),
            'retry' => $this->configStamp->getRetry(),
        ];
    }
}

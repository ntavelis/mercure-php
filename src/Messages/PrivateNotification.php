<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Messages;

use Ntavelis\Mercure\Config\ConfigStamp;
use Ntavelis\Mercure\Contracts\NotificationInterface;

class PrivateNotification implements NotificationInterface
{
    private array $topics;
    private array $data;
    private ConfigStamp $configStamp;

    public function __construct(array $topics, array $data)
    {
        $this->topics = $topics;
        $this->data = $data;
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

    public function getConfigStamp(): ConfigStamp
    {
        return $this->configStamp;
    }

    public function withConfig(ConfigStamp $configStamp): void
    {
        $this->configStamp = $configStamp;
    }

    public function getTokenData(): array
    {
        return $this->getTopics();
    }

    public function toArray(): array
    {
        return [
            'topic' => $this->getTopics(),
            'data' => json_encode($this->getData()),
            'id' => $this->configStamp->getId(),
            'type' => $this->configStamp->getType(),
            'retry' => $this->configStamp->getRetry(),
            'private' => 'on' # special flag, marks message private
        ];
    }
}

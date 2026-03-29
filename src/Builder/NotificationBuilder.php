<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Builder;

use Ntavelis\Mercure\Config\ConfigStamp;
use Ntavelis\Mercure\Messages\Notification;
use Ntavelis\Mercure\Messages\PrivateNotification;

class NotificationBuilder
{
    private array $topics = [];
    private array $data;
    /**
     * @var ConfigStamp|null
     */
    private ?ConfigStamp $configStamp = null;

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

    public function withConfig(ConfigStamp $configStamp): NotificationBuilder
    {
        $this->configStamp = $configStamp;

        return $this;
    }

    public function inPublic(): Notification
    {
        $notification = new Notification($this->topics, $this->data);

        if (isset($this->configStamp)) {
            $notification->withConfig($this->configStamp);
        }

        return $notification;
    }

    public function inPrivate(): PrivateNotification
    {
        $privateNotification = new PrivateNotification($this->topics, $this->data);

        if (isset($this->configStamp)) {
            $privateNotification->withConfig($this->configStamp);
        }

        return $privateNotification;
    }
}

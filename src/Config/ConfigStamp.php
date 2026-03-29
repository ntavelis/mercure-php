<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Config;

final class ConfigStamp
{
    private ?string $type;
    private ?string $id;
    private ?int $retry;

    public function __construct(?string $type = null, ?string $id = null, ?int $retry = null)
    {
        $this->type = $type;
        $this->id = $id;
        $this->retry = $retry;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRetry(): ?int
    {
        return $this->retry;
    }

    public function setType(string $type): ConfigStamp
    {
        $this->type = $type;

        return $this;
    }

    public function setId(string $id): ConfigStamp
    {
        $this->id = $id;

        return $this;
    }

    public function setRetry(int $retry): ConfigStamp
    {
        $this->retry = $retry;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Config;

final class ConfigStamp
{
    /**
     * The SSE's event property (a specific event type).
     * @var string
     */
    private $type;
    /**
     * The topic's revision identifier: it will be used as the SSE's id property.
     * If omitted, the hub MUST generate a valid globally unique id.
     * It MAY be a UUID (RFC4122). Even if provided,
     * the hub MAY ignore the id provided by the client and generate its own id.
     * @var string
     */
    private $id;
    /**
     * The SSE's retry property (the reconnection time).
     * @var int
     */
    private $retry;

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

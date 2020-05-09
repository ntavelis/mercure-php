<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Builder;

use Ntavelis\Mercure\Providers\PublisherTokenProvider;
use Ntavelis\Mercure\Publisher;
use Psr\Http\Client\ClientInterface;

class PublisherBuilder
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $key;
    /**
     * @var ClientInterface
     */
    private $psr18client;

    public function mercureHubUrl(string $url): PublisherBuilder
    {
        $this->url = $url;

        return $this;
    }

    public function key(string $key): PublisherBuilder
    {
        $this->key = $key;

        return $this;
    }

    public function psr18Client(ClientInterface $psr18client): PublisherBuilder
    {
        $this->psr18client = $psr18client;

        return $this;
    }

    public function get(): Publisher
    {
        return new Publisher(
            $this->url,
            new PublisherTokenProvider($this->key),
            $this->psr18client
        );
    }
}

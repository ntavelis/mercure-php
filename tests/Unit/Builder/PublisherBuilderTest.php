<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Builder;

use Ntavelis\Mercure\Builder\PublisherBuilder;
use Ntavelis\Mercure\Publisher;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

class PublisherBuilderTest extends TestCase
{
    /** @test */
    public function itCanBuildAMercureHubClientWithSensibleDefaults(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $publisher = (new PublisherBuilder())
            ->mercureHubUrl('http://localhost:3000')
            ->key('secretKey')
            ->psr18Client($client)
            ->get();

        $this->assertInstanceOf(Publisher::class, $publisher);
    }

    /** @test */
    public function itCanBuildTheClientFluentlyWithTheMethodsCalledInAnyOrder(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $publisher = (new PublisherBuilder())
            ->key('secretKey')
            ->psr18Client($client)
            ->mercureHubUrl('http://localhost:3000')
            ->get();

        $this->assertInstanceOf(Publisher::class, $publisher);
    }
}

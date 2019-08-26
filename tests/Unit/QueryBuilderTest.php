<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit;

use Ntavelis\Mercure\Messages\Notification;
use Ntavelis\Mercure\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    /** @test */
    public function itCanBuildAQueryStringGivenAMessageClass(): void
    {
        $notification = new Notification(['topics'], ['data']);

        $queryBuilder = new QueryBuilder($notification);

        $this->assertSame('topic=topics&data=%5B%22data%22%5D', (string)$queryBuilder);
    }

    /** @test */
    public function itCanBuildAQueryStringWithMultipleTopics(): void
    {
        $notification = new Notification(
            [
                'topic1',
                'topic2',
            ],
            ['data']
        );

        $queryBuilder = new QueryBuilder($notification);

        $this->assertSame('topic=topic1&topic=topic2&data=%5B%22data%22%5D', (string)$queryBuilder);
    }

    /** @test */
    public function itCanBuildAQueryStringAndItWillSkippNullValues(): void
    {
        $notification = new Notification(
            [
                'topics',
                null,
            ],
            ['data']
        );

        $queryBuilder = new QueryBuilder($notification);

        $this->assertSame('topic=topics&data=%5B%22data%22%5D', (string)$queryBuilder);
    }
}

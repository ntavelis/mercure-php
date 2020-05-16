<?php

declare(strict_types=1);

namespace Ntavelis\Mercure\Tests\Unit\Config;

use Ntavelis\Mercure\Config\ConfigStamp;
use PHPUnit\Framework\TestCase;

class ConfigStampTest extends TestCase
{
    /** @test */
    public function itContainsConfigurationValuesThatCanBeUsedToNotificationMessages(): void
    {
        $configStamp = new ConfigStamp('notification', '1234', 30);

        $this->assertSame('notification', $configStamp->getType());
        $this->assertSame('1234', $configStamp->getId());
        $this->assertSame(30, $configStamp->getRetry());
    }

    /** @test */
    public function itCanBeConfiguredThroughSetterFunctions(): void
    {
        $configStamp = new ConfigStamp();

        $configStamp->setId('1234');
        $configStamp->setType('notification');
        $configStamp->setRetry(30);

        $this->assertSame('notification', $configStamp->getType());
        $this->assertSame('1234', $configStamp->getId());
        $this->assertSame(30, $configStamp->getRetry());
    }

    /** @test */
    public function itCanBeConfiguredThroughSetterFunctionsInAFluentWay(): void
    {
        $configStamp = new ConfigStamp();

        $configStamp->setId('1234')->setType('notification')->setRetry(30);

        $this->assertSame('notification', $configStamp->getType());
        $this->assertSame('1234', $configStamp->getId());
        $this->assertSame(30, $configStamp->getRetry());
    }
}

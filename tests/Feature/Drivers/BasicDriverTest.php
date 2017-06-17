<?php

namespace Clarkeash\Doorman\Test\Feature\Drivers;

use Clarkeash\Doorman\DoormanManager;
use Clarkeash\Doorman\Drivers\BasicDriver;
use Clarkeash\Doorman\Drivers\DriverInterface;
use Clarkeash\Doorman\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Assert;

class BasicDriverTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_is_a_driver()
    {
        Assert::assertInstanceOf(DriverInterface::class, new BasicDriver);
    }

    /**
     * @test
     */
    public function it_is_the_correct_driver()
    {
        Assert::assertInstanceOf(BasicDriver::class, app(DoormanManager::class)->driver('basic'));
    }

    /**
     * @test
     */
    public function it_is_the_default_driver()
    {
        Assert::assertSame('basic', config('doorman.driver'));
        Assert::assertSame('basic', app(DoormanManager::class)->getDefaultDriver());
    }

    /**
     * @test
     */
    public function it_has_a_default_length()
    {
        Assert::assertSame(5, config('doorman.basic.length'));

        $driver = new BasicDriver;

        Assert::assertSame(5, strlen($driver->code()));
    }

    /**
     * @test
     */
    public function it_allows_the_length_to_be_overridden()
    {
        Assert::assertSame(5, config('doorman.basic.length'));

        $this->app['config']['doorman.basic.length'] = 8;

        Assert::assertSame(8, config('doorman.basic.length'));

        $driver = new BasicDriver;

        Assert::assertSame(8, strlen($driver->code()));
    }
}

<?php

namespace Clarkeash\Doorman\Test\Feature\Drivers;

use Clarkeash\Doorman\DoormanManager;
use Clarkeash\Doorman\Drivers\DriverInterface;
use Clarkeash\Doorman\Drivers\UuidDriver;
use Clarkeash\Doorman\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Assert;
use Ramsey\Uuid\Uuid;

class UuidDriverTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_is_a_driver()
    {
        Assert::assertInstanceOf(DriverInterface::class, new UuidDriver);
    }

    /**
     * @test
     */
    public function it_is_the_correct_driver()
    {
        Assert::assertInstanceOf(UuidDriver::class, app(DoormanManager::class)->driver('uuid'));
    }

    /** @test */
    public function it_can_generate_a_version_1_uuid()
    {
        $this->app['config']['doorman.uuid.version'] = 1;

        $driver = new UuidDriver;

        $code = $driver->code();

        Assert::assertRegExp('/' . Uuid::VALID_PATTERN . '/', $code);
        Assert::assertSame(1, Uuid::fromString($code)->getVersion());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_throws_exception_if_invalid_version_supplied()
    {
        $this->app['config']['doorman.uuid.version'] = 2;

        $driver = new UuidDriver;

        $driver->code();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Namespace must be set for uuid version 3
     */
    public function namespace_is_required_for_version_3()
    {
        $this->app['config']['doorman.uuid.version'] = 3;
        $this->app['config']['doorman.uuid.name'] = 'example';

        $driver = new UuidDriver;

        $driver->code();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Name must be set for uuid version 3
     */
    public function name_is_required_for_version_3()
    {
        $this->app['config']['doorman.uuid.version'] = 3;
        $this->app['config']['doorman.uuid.namespace'] = 'example';

        $driver = new UuidDriver;

        $driver->code();
    }


    /** @test */
    public function it_can_generate_a_version_3_uuid()
    {
        $this->app['config']['doorman.uuid.version'] = 3;
        $this->app['config']['doorman.uuid.namespace'] = Uuid::NAMESPACE_DNS;
        $this->app['config']['doorman.uuid.name'] = 'ashleyclarke.me';

        $driver = new UuidDriver;

        $code = $driver->code();

        Assert::assertRegExp('/' . Uuid::VALID_PATTERN . '/', $code);
        Assert::assertSame(3, Uuid::fromString($code)->getVersion());
    }

    /** @test */
    public function it_can_generate_a_version_4_uuid()
    {
        $this->app['config']['doorman.uuid.version'] = 4;

        $driver = new UuidDriver;

        $code = $driver->code();

        Assert::assertRegExp('/' . Uuid::VALID_PATTERN . '/', $code);
        Assert::assertSame(4, Uuid::fromString($code)->getVersion());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Namespace must be set for uuid version 5
     */
    public function namespace_is_required_for_version_5()
    {
        $this->app['config']['doorman.uuid.version'] = 5;
        $this->app['config']['doorman.uuid.name'] = 'example';

        $driver = new UuidDriver;

        $driver->code();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Name must be set for uuid version 5
     */
    public function name_is_required_for_version_5()
    {
        $this->app['config']['doorman.uuid.version'] = 5;
        $this->app['config']['doorman.uuid.namespace'] = 'example';

        $driver = new UuidDriver;

        $driver->code();
    }


    /** @test */
    public function it_can_generate_a_version_5_uuid()
    {
        $this->app['config']['doorman.uuid.version'] = 5;
        $this->app['config']['doorman.uuid.namespace'] = Uuid::NAMESPACE_DNS;
        $this->app['config']['doorman.uuid.name'] = 'ashleyclarke.me';

        $driver = new UuidDriver;

        $code = $driver->code();

        Assert::assertRegExp('/' . Uuid::VALID_PATTERN . '/', $code);
        Assert::assertSame(5, Uuid::fromString($code)->getVersion());
    }
}

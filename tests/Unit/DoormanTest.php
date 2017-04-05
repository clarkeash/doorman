<?php

namespace Clarkeash\Doorman\Test\Unit;

use Doorman;
use Clarkeash\Doorman\Generator;
use Clarkeash\Doorman\Test\TestCase;
use PHPUnit\Framework\Assert;

class DoormanTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_a_generator()
    {
        $generator = Doorman::generate();

        Assert::assertInstanceOf(Generator::class, $generator);
    }
}

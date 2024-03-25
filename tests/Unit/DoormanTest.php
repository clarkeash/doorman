<?php

namespace Clarkeash\Doorman\Test\Unit;

use Doorman;
use Clarkeash\Doorman\Generator;
use Clarkeash\Doorman\Test\TestCase;
use PHPUnit\Framework\Assert;

class DoormanTest extends TestCase
{
    public function test_it_provides_a_generator()
    {
        $generator = Doorman::generate();

        Assert::assertInstanceOf(Generator::class, $generator);
    }
}

<?php

namespace Clarkeash\Doorman\Test\Feature;

use Carbon\Carbon;
use Clarkeash\Doorman\Models\Invite;
use Clarkeash\Doorman\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Assert;

class InviteHelperTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function check_if_it_has_expired()
    {
        /** @var Invite $one */
        $one = Invite::forceCreate([
            'code' => 'ONE'
        ]);

        Assert::assertFalse($one->hasExpired());

        /** @var Invite $two */
        $two = Invite::forceCreate([
            'code' => 'TWO',
            'valid_until' => Carbon::now()->addMinutes(5)
        ]);

        Assert::assertFalse($two->hasExpired());

        /** @var Invite $three */
        $three = Invite::forceCreate([
            'code' => 'THREE',
            'valid_until' => Carbon::now()->subMinutes(5)
        ]);

        Assert::assertTrue($three->hasExpired());
    }

    /**
     * @test
     */
    public function check_if_it_is_full()
    {
        /** @var Invite $one */
        $one = Invite::forceCreate([
            'code' => 'ONE',
            'uses' => 0,
            'max' => 0
        ]);

        Assert::assertFalse($one->isFull());

        /** @var Invite $two */
        $two = Invite::forceCreate([
            'code' => 'TWO',
            'uses' => 5,
            'max' => 0
        ]);

        Assert::assertFalse($two->isFull());

        /** @var Invite $three */
        $three = Invite::forceCreate([
            'code' => 'THREE',
            'uses' => 5,
            'max' => 5
        ]);

        Assert::assertTrue($three->isFull());
    }
}

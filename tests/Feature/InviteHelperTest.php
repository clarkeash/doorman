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
        $one = Invite::forceCreate([
            'code' => 'ONE'
        ]);

        Assert::assertFalse($one->expired);

        $two = Invite::forceCreate([
            'code' => 'TWO',
            'valid_until' => Carbon::now()->addMinutes(5)
        ]);

        Assert::assertFalse($two->expired);

        $three = Invite::forceCreate([
            'code' => 'THREE',
            'valid_until' => Carbon::now()->subMinutes(5)
        ]);

        Assert::assertTrue($three->expired);
    }

    /**
     * @test
     */
    public function check_if_it_is_full()
    {
        $one = Invite::forceCreate([
            'code' => 'ONE',
            'uses' => 0,
            'max' => 0
        ]);

        Assert::assertFalse($one->full);

        $two = Invite::forceCreate([
            'code' => 'TWO',
            'uses' => 5,
            'max' => 0
        ]);

        Assert::assertFalse($two->full);

        $three = Invite::forceCreate([
            'code' => 'THREE',
            'uses' => 5,
            'max' => 5
        ]);

        Assert::assertTrue($three->full);
    }
}

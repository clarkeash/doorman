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

    /**
     * @test
     */
    public function check_if_it_is_restricted()
    {
        /** @var Invite $one */
        $one = Invite::forceCreate([
            'code' => 'ONE'
        ]);

        Assert::assertFalse($one->isRestricted());

        /** @var Invite $two */
        $two = Invite::forceCreate([
            'code' => 'TWO',
            'for' => 'user@example.com'
        ]);

        Assert::assertTrue($two->isRestricted());
    }

    /**
     * @test
     */
    public function check_if_it_is_restricted_for_a_particular_person()
    {
        /** @var Invite $one */
        $one = Invite::forceCreate([
            'code' => 'ONE'
        ]);

        Assert::assertFalse($one->isRestrictedFor('user@example.com'));

        /** @var Invite $two */
        $two = Invite::forceCreate([
            'code' => 'TWO',
            'for' => 'user@example.com'
        ]);

        Assert::assertTrue($two->isRestrictedFor('user@example.com'));
    }

    /**
     * @test
     */
    public function check_if_it_is_useless()
    {
        /** @var Invite $one */
        $one = Invite::forceCreate([
            'code' => 'ONE'
        ]);

        Assert::assertFalse($one->isUseless());

        /** @var Invite $two */
        $two = Invite::forceCreate([
            'code' => 'TWO',
            'valid_until' => Carbon::now()->subMinutes(5)
        ]);

        Assert::assertTrue($two->isUseless());

        /** @var Invite $three */
        $three = Invite::forceCreate([
            'code' => 'THREE',
            'uses' => 5,
            'max' => 5
        ]);

        Assert::assertTrue($three->isUseless());

        /** @var Invite $four */
        $four = Invite::forceCreate([
            'code' => 'FOUR',
            'uses' => 5,
            'max' => 5,
            'valid_until' => Carbon::now()->subMinutes(5)
        ]);

        Assert::assertTrue($four->isUseless());
    }

    /**
     * @test
     */
    public function expired_scope()
    {
        Invite::forceCreate([
            'code' => 'ONE'
        ]);

        Invite::forceCreate([
            'code' => 'TWO',
            'valid_until' => Carbon::now()->addMinutes(5)
        ]);

        Invite::forceCreate([
            'code' => 'THREE',
            'valid_until' => Carbon::now()->subMinutes(5)
        ]);

        Assert::assertEquals(1, Invite::expired()->count());
    }

    /**
     * @test
     */
    public function full_scope()
    {
        Invite::forceCreate([
            'code' => 'ONE',
            'uses' => 0,
            'max' => 0
        ]);

        Invite::forceCreate([
            'code' => 'TWO',
            'uses' => 5,
            'max' => 0
        ]);

        Invite::forceCreate([
            'code' => 'THREE',
            'uses' => 5,
            'max' => 5
        ]);

        Assert::assertEquals(1, Invite::full()->count());
    }

    /**
     * @test
     */
    public function useless_scope()
    {
        Invite::forceCreate([
            'code' => 'ONE'
        ]);

        Invite::forceCreate([
            'code' => 'TWO',
            'valid_until' => Carbon::now()->addMinutes(5)
        ]);

        Invite::forceCreate([
            'code' => 'THREE',
            'valid_until' => Carbon::now()->subMinutes(5)
        ]);

        Invite::forceCreate([
            'code' => 'FOUR',
            'uses' => 0,
            'max' => 0
        ]);

        Invite::forceCreate([
            'code' => 'FIVE',
            'uses' => 5,
            'max' => 0
        ]);

        Invite::forceCreate([
            'code' => 'SIX',
            'uses' => 5,
            'max' => 5
        ]);

        Assert::assertEquals(2, Invite::useless()->count());
    }
}

<?php

namespace Clarkeash\Doorman\Test\Feature;

use Carbon\Carbon;
use Clarkeash\Doorman\Models\Invite;
use Doorman;
use Clarkeash\Doorman\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Assert;

class RedeemInvitesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @expectedException \Clarkeash\Doorman\Exceptions\InvalidInviteCode
     * @expectedExceptionMessage The invite code NOPE is invalid.
     */
    public function it_squawks_if_code_is_invalid()
    {
        Doorman::redeem('NOPE');
    }

    /**
     * @test
     */
    public function it_increments_uses_if_valid_code()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'max' => 2,
            'uses' => 1,
            'valid_until' => Carbon::now()->addDays(3)
        ]);

        Doorman::redeem('ABCDE');

        $invite = Invite::where('code', '=', 'ABCDE')->firstOrFail();

        Assert::assertEquals(2, $invite->uses);
    }

    /**
     * @test
     * @expectedException \Clarkeash\Doorman\Exceptions\MaxUsesReached
     * @expectedExceptionMessage The invite code ABCDE has already been used the maximum number of times.
     */
    public function it_squawks_if_maximum_uses_has_been_reached()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'max' => 2,
            'uses' => 2,
        ]);

        Doorman::redeem('ABCDE');
    }

    /**
     * @test
     * @expectedException \Clarkeash\Doorman\Exceptions\ExpiredInviteCode
     * @expectedExceptionMessage The invite code ABCDE has expired.
     */
    public function it_squawks_if_code_has_expired()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'valid_until' => Carbon::now()->subDay(),
        ]);

        Doorman::redeem('ABCDE');
    }

    /**
     * @test
     * @expectedException \Clarkeash\Doorman\Exceptions\NotYourInviteCode
     * @expectedExceptionMessage The invite code ABCDE belongs to another user.
     */
    public function it_squawks_if_trying_to_use_a_code_belonging_to_someone_else()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'for' => 'me@ashleyclarke.me'
        ]);

        Doorman::redeem('ABCDE');
    }

    /**
     * @test
     */
    public function it_can_redeem_a_code_for_a_specific_user()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'for' => 'me@ashleyclarke.me'
        ]);

        Doorman::redeem('ABCDE', 'me@ashleyclarke.me');

        $invite = Invite::where('code', '=', 'ABCDE')->firstOrFail();

        Assert::assertEquals(1, $invite->uses);
    }

    /**
     * @test
     */
    public function email_address_is_case_insensitive_1()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'for' => 'ME@ASHLEYCLARKE.ME'
        ]);

        Doorman::redeem('ABCDE', 'me@ashleyclarke.me');

        $invite = Invite::where('code', '=', 'ABCDE')->firstOrFail();

        Assert::assertEquals(1, $invite->uses);
    }

    /**
     * @test
     */
    public function email_address_is_case_insensitive_2()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'for' => 'me@ashleyclarke.me'
        ]);

        Doorman::redeem('ABCDE', 'ME@ASHLEYCLARKE.ME');

        $invite = Invite::where('code', '=', 'ABCDE')->firstOrFail();

        Assert::assertEquals(1, $invite->uses);
    }

    /**
     * @test
     */
    public function a_unrestricted_invite_can_be_redeemed_when_email_is_provided()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
        ]);

        Doorman::redeem('ABCDE', 'me@ashleyclarke.me');

        $invite = Invite::where('code', '=', 'ABCDE')->firstOrFail();

        Assert::assertEquals(1, $invite->uses);
    }

    /**
     * @test
     */
    public function it_can_have_unlimited_redemptions()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'max' => 0,
        ]);

        for ($i = 0; $i < 10; $i++) {
            Doorman::redeem('ABCDE');
        }

        $invite = Invite::where('code', '=', 'ABCDE')->firstOrFail();

        Assert::assertEquals(10, $invite->uses);
    }
}

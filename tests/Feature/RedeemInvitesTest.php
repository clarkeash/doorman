<?php

namespace Clarkeash\Doorman\Test\Feature;

use Carbon\Carbon;
use Clarkeash\Doorman\Exceptions\ExpiredInviteCode;
use Clarkeash\Doorman\Exceptions\InvalidInviteCode;
use Clarkeash\Doorman\Exceptions\MaxUsesReached;
use Clarkeash\Doorman\Exceptions\NotYourInviteCode;
use Clarkeash\Doorman\Models\Invite;
use Doorman;
use Clarkeash\Doorman\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Assert;

class RedeemInvitesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_squawks_if_code_is_invalid()
    {
        $this->expectException(InvalidInviteCode::class);
        $this->expectExceptionMessage('The invite code NOPE is invalid.');

        Doorman::redeem('NOPE');
    }

    public function test_it_increments_uses_if_valid_code()
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

    public function test_it_squawks_if_maximum_uses_has_been_reached()
    {
        $this->expectException(MaxUsesReached::class);
        $this->expectExceptionMessage('The invite code ABCDE has already been used the maximum number of times.');

        Invite::forceCreate([
            'code' => 'ABCDE',
            'max' => 2,
            'uses' => 2,
        ]);

        Doorman::redeem('ABCDE');
    }

    public function test_it_squawks_if_code_has_expired()
    {
        $this->expectException(ExpiredInviteCode::class);
        $this->expectExceptionMessage('The invite code ABCDE has expired.');

        Invite::forceCreate([
            'code' => 'ABCDE',
            'valid_until' => Carbon::now()->subDay(),
        ]);

        Doorman::redeem('ABCDE');
    }

    public function test_it_squawks_if_trying_to_use_a_code_belonging_to_someone_else()
    {
        $this->expectException(NotYourInviteCode::class);
        $this->expectExceptionMessage('The invite code ABCDE belongs to another user.');

        Invite::forceCreate([
            'code' => 'ABCDE',
            'for' => 'me@ashleyclarke.me'
        ]);

        Doorman::redeem('ABCDE');
    }

    public function test_it_can_redeem_a_code_for_a_specific_user()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'for' => 'me@ashleyclarke.me'
        ]);

        Doorman::redeem('ABCDE', 'me@ashleyclarke.me');

        $invite = Invite::where('code', '=', 'ABCDE')->firstOrFail();

        Assert::assertEquals(1, $invite->uses);
    }

    public function test_email_address_is_case_insensitive_1()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'for' => 'ME@ASHLEYCLARKE.ME'
        ]);

        Doorman::redeem('ABCDE', 'me@ashleyclarke.me');

        $invite = Invite::where('code', '=', 'ABCDE')->firstOrFail();

        Assert::assertEquals(1, $invite->uses);
    }

    public function test_email_address_is_case_insensitive_2()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'for' => 'me@ashleyclarke.me'
        ]);

        Doorman::redeem('ABCDE', 'ME@ASHLEYCLARKE.ME');

        $invite = Invite::where('code', '=', 'ABCDE')->firstOrFail();

        Assert::assertEquals(1, $invite->uses);
    }

    public function test_a_unrestricted_invite_can_be_redeemed_when_email_is_provided()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
        ]);

        Doorman::redeem('ABCDE', 'me@ashleyclarke.me');

        $invite = Invite::where('code', '=', 'ABCDE')->firstOrFail();

        Assert::assertEquals(1, $invite->uses);
    }

    public function test_it_can_have_unlimited_redemptions()
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

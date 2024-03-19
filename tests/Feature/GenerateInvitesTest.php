<?php

namespace Clarkeash\Doorman\Test\Feature;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Clarkeash\Doorman\Exceptions\DuplicateException;
use Clarkeash\Doorman\Models\Invite;
use Doorman;
use Clarkeash\Doorman\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Assert;

class GenerateInvitesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_will_generate_one_invite_by_default()
    {
        Doorman::generate()->make();

        Assert::assertCount(1, Invite::all());
    }

    public function test_it_can_generate_multiple_invites()
    {
        Doorman::generate()->times(5)->make();

        Assert::assertCount(5, Invite::all());
    }

    public function test_it_has_one_use_by_default()
    {
        Doorman::generate()->make();

        $invite = Invite::first();

        Assert::assertEquals(1, $invite->max);
    }

    public function test_it_can_have_multiple_uses()
    {
        Doorman::generate()->uses(10)->make();

        $invite = Invite::first();

        Assert::assertEquals(10, $invite->max);
    }

    public function test_it_can_have_an_expiry_date()
    {
        $date = Carbon::now('UTC')->endOfDay();

        Doorman::generate()->expiresOn($date)->make();

        $invite = Invite::first();

        Assert::assertLessThan(1, $date->floatDiffInSeconds($invite->valid_until));
    }

    public function test_it_can_accept_immutable_date()
    {
        $date = CarbonImmutable::now('UTC')->endOfDay();

        Doorman::generate()->expiresOn($date)->make();

        $invite = Invite::first();

        Assert::assertLessThan(1, $date->floatDiffInSeconds($invite->valid_until));
    }

    public function test_it_can_accept_vanilla_date()
    {
        $date = (new \DateTime)->setTime(23, 59, 59);

        Doorman::generate()->expiresOn($date)->make();

        $invite = Invite::first();

        Assert::assertLessThan(1, $date->diff($invite->valid_until)->format('%s'));
    }

    public function test_it_can_accept_vanilla_immutable_date()
    {
        $date = (new \DateTimeImmutable)->setTime(23, 59, 59);

        Doorman::generate()->expiresOn($date)->make();

        $invite = Invite::first();

        Assert::assertLessThan(1, $date->diff($invite->valid_until)->format('%s'));
    }

    public function test_it_has_helper_to_set_number_of_days_in_future_to_expire()
    {
        Doorman::generate()->expiresIn(7)->make();

        $invite = Invite::first();

        $date = Carbon::now('UTC')->addDays(7)->endOfDay()->second(59);

        Assert::assertLessThan(1, $date->floatDiffInSeconds($invite->valid_until));
    }

    public function test_it_can_be_valid_for_a_single_email()
    {
        Doorman::generate()->for('me@ashleyclarke.me')->make();

        $invite = Invite::first();

        Assert::assertEquals('me@ashleyclarke.me', $invite->for);
    }

    public function test_only_one_invite_per_email_can_be_generated_1()
    {
        $this->expectException(DuplicateException::class);

        Doorman::generate()->for('me@ashleyclarke.me')->make();
        Doorman::generate()->for('me@ashleyclarke.me')->make();
    }

    public function test_only_one_invite_per_email_can_be_generated_2()
    {
        $this->expectException(DuplicateException::class);

        Doorman::generate()->for('me@ashleyclarke.me')->times(3)->make();
    }

    public function test_generated_codes_should_always_be_uppercase()
    {
        Doorman::generate()->make();

        $invite = Invite::first();

        Assert::assertEquals(strtoupper($invite->code), $invite->code);
    }

    public function test_it_will_generate_an_invite_only_once()
    {
        $invite = Doorman::generate()->once();

        Assert::assertInstanceOf(Invite::class, $invite);
    }

    public function test_it_will_generate_an_invite_with_unlimited_redemptions()
    {
        $invite = Doorman::generate()->unlimited()->once();

        Assert::assertEquals(0, $invite->uses);
    }
}

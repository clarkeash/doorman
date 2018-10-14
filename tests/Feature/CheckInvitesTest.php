<?php

namespace Clarkeash\Doorman\Test\Feature;

use Carbon\Carbon;
use Clarkeash\Doorman\Models\Invite;
use Doorman;
use Clarkeash\Doorman\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Assert;

class CheckInvitesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function check_if_code_is_invalid()
    {
        Assert::assertFalse(Doorman::check('NOPE'));
    }

    /**
     * @test
     */
    public function check_if_maximum_uses_has_been_reached()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'max' => 2,
            'uses' => 2,
        ]);

        Assert::assertFalse(Doorman::check('ABCDE'));
    }

    /**
     * @test
     */
    public function check_if_code_has_expired()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'valid_until' => Carbon::now()->subDay(),
        ]);

        Assert::assertFalse(Doorman::check('ABCDE'));
    }

    /**
     * @test
     */
    public function check_if_trying_to_use_a_code_belonging_to_someone_else()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'for' => 'me@ashleyclarke.me'
        ]);

        Assert::assertFalse(Doorman::check('ABCDE'));
    }

    /**
     * @test
     */
    public function check_a_code_for_a_specific_user()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'for' => 'me@ashleyclarke.me'
        ]);

        Assert::assertTrue(Doorman::check('ABCDE', 'me@ashleyclarke.me'));
    }

    /**
     * @test
     */
    public function a_unrestricted_invite_can_be_redeemed_when_email_is_provided()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
        ]);

        Assert::assertTrue(Doorman::check('ABCDE', 'me@ashleyclarke.me'));
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
            Assert::assertTrue(Doorman::check('ABCDE'));
        }
    }

    /**
    * @test
    */
    public function it_is_not_case_sensitive()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
        ]);

        Assert::assertTrue(Doorman::check('ABCDE'));
        Assert::assertTrue(Doorman::check('abcde'));
        Assert::assertTrue(Doorman::check('AbCdE'));
    }
}

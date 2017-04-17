<?php

namespace Clarkeash\Doorman\Test\Feature;

use Clarkeash\Doorman\Models\Invite;
use Doorman;
use Clarkeash\Doorman\Test\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Assert;
use Validator;

class CustomValidationRuleTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function basic_validation()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'max' => 2,
        ]);

        $rules = [
            'code' => 'doorman'
        ];

        $data = [
            'code' => 'ABCDE',
        ];

        $validator = Validator::make($data, $rules);

        Assert::assertTrue($validator->passes());
    }

    /**
     * @test
     */
    public function validates_email_address()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'max' => 2,
            'for' => 'me@ashleyclarke.me'
        ]);

        $rules = [
            'code' => 'doorman:email'
        ];

        $data = [
            'code' => 'ABCDE',
            'email' => 'me@ashleyclarke.me'
        ];

        $validator = Validator::make($data, $rules);

        Assert::assertTrue($validator->passes());
    }

    /**
     * @test
     */
    public function will_try_to_guess_email_field_if_not_specified()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'max' => 2,
            'for' => 'me@ashleyclarke.me'
        ]);

        $rules = [
            'code' => 'doorman'
        ];

        // Try with 'email'
        $data = [
            'code' => 'ABCDE',
            'email' => 'me@ashleyclarke.me'
        ];

        $validator = Validator::make($data, $rules);

        Assert::assertTrue($validator->passes());

        // try with 'email_address'
        $data = [
            'code' => 'ABCDE',
            'email_address' => 'me@ashleyclarke.me'
        ];

        $validator = Validator::make($data, $rules);

        Assert::assertTrue($validator->passes());
    }

    /**
     * @test
     */
    public function it_provides_an_error_message()
    {
        Invite::forceCreate([
            'code' => 'ABCDE',
            'max' => 2,
            'for' => 'me@ashleyclarke.me'
        ]);

        $rules = [
            'code' => 'doorman:email'
        ];

        $data = [
            'code' => 'ABCDE',
            'email' => 'wrong@email.com'
        ];

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($data, $rules);

        Assert::assertTrue($validator->fails());

        Assert::assertEquals('The invite code ABCDE belongs to another user.', $validator->getMessageBag()->first('code'));
    }
}

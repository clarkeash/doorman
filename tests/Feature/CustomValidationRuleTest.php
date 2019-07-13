<?php

namespace Clarkeash\Doorman\Test\Feature;

use Clarkeash\Doorman\Models\Invite;
use Clarkeash\Doorman\Validation\DoormanRule;
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
            'code' => new DoormanRule()
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

        $data = [
            'code' => 'ABCDE',
            'email' => 'me@ashleyclarke.me'
        ];

        $rules = [
            'code' => new DoormanRule($data['email'])
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

        $data = [
            'code' => 'ABCDE',
            'email' => 'wrong@email.com'
        ];

        $rules = [
            'code' => new DoormanRule($data['email'])
        ];

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($data, $rules);

        Assert::assertTrue($validator->fails());

        Assert::assertEquals('The invite code ABCDE belongs to another user.', $validator->getMessageBag()->first('code'));
    }
}

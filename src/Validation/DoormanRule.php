<?php

namespace Clarkeash\Doorman\Validation;


use Illuminate\Contracts\Validation\Rule;

class DoormanRule implements Rule
{
    /**
     * @var \Clarkeash\Doorman\Doorman
     */
    private $doorman;

    /**
     * @var string|null
     */
    private $email;

    public function __construct($email = null)
    {
        $this->doorman = app(\Clarkeash\Doorman\Doorman::class);
        $this->email = $email;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->doorman->check($value, $this->email);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return $this->doorman->error;
    }
}
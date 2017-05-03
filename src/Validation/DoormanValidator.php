<?php

namespace Clarkeash\Doorman\Validation;

use Clarkeash\Doorman\Doorman;
use Illuminate\Validation\Validator;

class DoormanValidator
{
    /**
     * @var \Clarkeash\Doorman\Doorman
     */
    private $manager;

    public function __construct(Doorman $manager)
    {
        $this->manager = $manager;
    }

    public function validate($attribute, $value, $parameters, Validator $validator)
    {
        $email = $this->getEmailAddress($parameters, $validator);

        return $this->manager->check($value, $email);
    }

    public function replace($message, $attribute, $rule, $parameters)
    {
        return $this->manager->error;
    }

    protected function getParameterName($parameters, Validator $validator)
    {
        if (isset($parameters[ 0 ])) {
            return $parameters[ 0 ];
        }

        $possibles = [ 'email', 'email_address' ];

        foreach ($possibles as $possible) {
            if (isset($validator->getData()[ $possible ])) {
                return $possible;
            }
        }
    }

    protected function getEmailAddress($parameters, Validator $validator)
    {
        $field = $this->getParameterName($parameters, $validator);

        if ($field) {
            return $validator->getData()[ $field ];
        }
    }
}

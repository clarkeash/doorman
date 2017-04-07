<?php

namespace Clarkeash\Doorman;

class Doorman
{
    public function redeem($code, $email = null)
    {
        return (new Manager)->redeem($code, $email);
    }

    public function generate()
    {
        return new Generator;
    }
}

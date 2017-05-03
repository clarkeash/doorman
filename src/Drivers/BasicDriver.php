<?php

namespace Clarkeash\Doorman\Drivers;

use Illuminate\Support\Str;

class BasicDriver implements DriverInterface
{

    /**
     * Create an invite code.
     *
     * @return string
     */
    public function code(): string
    {
        return Str::random(config('doorman.basic.length', 5));
    }
}

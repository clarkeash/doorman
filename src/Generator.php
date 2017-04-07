<?php

namespace Clarkeash\Doorman;

use Carbon\Carbon;
use Clarkeash\Doorman\Models\Invite;
use Illuminate\Support\Str;

class Generator
{
    protected $amount = 1;
    protected $uses = 1;
    protected $email;
    protected $expiry;


    public function times(int $amount = 1)
    {
        $this->amount = $amount;

        return $this;
    }

    public function uses(int $amount = 1)
    {
        $this->uses = $amount;

        return $this;
    }

    public function for (string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function expiresOn(Carbon $date)
    {
        $this->expiry = $date;

        return $this;
    }

    public function expiresIn($days = 14)
    {
        $this->expiry = Carbon::now(config('app.timezone'))->addDays($days)->endOfDay();

        return $this;
    }

    protected function build(): Invite
    {
        $invite = new Invite;
        $invite->code = Str::upper(Str::random(5));
        $invite->for = $this->email;
        $invite->max = $this->uses;
        $invite->valid_until = $this->expiry;

        return $invite;
    }

    public function make()
    {
        for ($i = 0; $i < $this->amount; $i++) {
            $this->build()->save();
        }
    }
}

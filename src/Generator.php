<?php

namespace Clarkeash\Doorman;

use Carbon\Carbon;
use Clarkeash\Doorman\Exceptions\DuplicateException;
use Clarkeash\Doorman\Models\Invite;
use Illuminate\Support\Str;

class Generator
{
    protected $amount = 1;
    protected $uses = 1;
    protected $email = null;
    protected $expiry;

    /**
     * @var \Clarkeash\Doorman\DoormanManager
     */
    protected $manager;

    public function __construct(DoormanManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function times(int $amount = 1)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function uses(int $amount = 1)
    {
        $this->uses = $amount;

        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     * @throws \Clarkeash\Doorman\Exceptions\DuplicateException
     */
    public function for(string $email)
    {
        if (Invite::where('for', strtolower($email))->first()) {
            throw new DuplicateException('You cannot create more than 1 invite code for an email');
        }

        $this->email = $email;

        return $this;
    }

    /**
     * @param \Carbon\Carbon $date
     *
     * @return $this
     */
    public function expiresOn(Carbon $date)
    {
        $this->expiry = $date;

        return $this;
    }

    /**
     * @param int $days
     *
     * @return $this
     */
    public function expiresIn($days = 14)
    {
        $this->expiry = Carbon::now(config('app.timezone'))->addDays($days)->endOfDay();

        return $this;
    }

    /**
     * @return \Clarkeash\Doorman\Models\Invite
     */
    protected function build(): Invite
    {
        $invite = new Invite;
        $invite->code = Str::upper($this->manager->code());
        $invite->for = $this->email;
        $invite->max = $this->uses;
        $invite->valid_until = $this->expiry;

        return $invite;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function make()
    {
        $invites = collect();

        if (!is_null($this->email) && $this->amount > 1) {
            throw new DuplicateException('You cannot create more than 1 invite code for an email');
        }

        for ($i = 0; $i < $this->amount; $i++) {
            $invite = $this->build();

            $invites->push($invite);

            $invite->save();
        }

        return $invites;
    }
}

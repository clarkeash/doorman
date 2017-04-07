<?php

namespace Clarkeash\Doorman;

use Clarkeash\Doorman\Exceptions\ExpiredInviteCode;
use Clarkeash\Doorman\Exceptions\InvalidInviteCode;
use Clarkeash\Doorman\Exceptions\MaxUsesReached;
use Clarkeash\Doorman\Exceptions\NotYourInviteCode;
use Clarkeash\Doorman\Models\Invite;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class Manager
{
    public function redeem($code, $email = null)
    {
        $invite = $this->lookupInvite($code);

        $this->validateInvite($invite, $email);

        $invite->increment('uses');
    }

    protected function lookupInvite($code): Invite
    {
        try {
            return Invite::where('code', '=', Str::upper($code))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new InvalidInviteCode;
        }
    }

    protected function validateInvite(Invite $invite, $email = null)
    {
        if ($invite->max != 0 && $invite->uses >= $invite->max) {
            throw new MaxUsesReached;
        }

        if (!is_null($invite->valid_until) && $invite->valid_until->isPast()) {
            throw new ExpiredInviteCode;
        }

        if (!is_null($invite->for) && $invite->for != $email) {
            throw new NotYourInviteCode;
        }
    }
}

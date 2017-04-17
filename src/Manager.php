<?php

namespace Clarkeash\Doorman;

use Clarkeash\Doorman\Exceptions\DoormanException;
use Clarkeash\Doorman\Exceptions\ExpiredInviteCode;
use Clarkeash\Doorman\Exceptions\InvalidInviteCode;
use Clarkeash\Doorman\Exceptions\MaxUsesReached;
use Clarkeash\Doorman\Exceptions\NotYourInviteCode;
use Clarkeash\Doorman\Models\Invite;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class Manager
{
    public $error = '';

    /**
     * @param             $code
     * @param string|null $email
     */
    public function redeem($code, string $email = null)
    {
        $invite = $this->prep($code, $email);

        $invite->increment('uses');
    }

    /**
     * @param             $code
     * @param string|null $email
     *
     * @return bool
     */
    public function check($code, string $email = null)
    {
        try {
            $this->prep($code, $email);
            return true;
        } catch (DoormanException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    protected function prep($code, string $email = null)
    {
        $this->error = '';
        $invite = $this->lookupInvite($code);
        $this->validateInvite($invite, $email);

        return $invite;
    }

    /**
     * @param $code
     *
     * @return \Clarkeash\Doorman\Models\Invite
     * @throws \Clarkeash\Doorman\Exceptions\InvalidInviteCode
     */
    protected function lookupInvite($code): Invite
    {
        try {
            return Invite::where('code', '=', Str::upper($code))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new InvalidInviteCode(trans('doorman::messages.invalid', [ 'code' => $code ]));
        }
    }

    /**
     * @param \Clarkeash\Doorman\Models\Invite $invite
     * @param string|null                      $email
     *
     * @throws \Clarkeash\Doorman\Exceptions\ExpiredInviteCode
     * @throws \Clarkeash\Doorman\Exceptions\MaxUsesReached
     * @throws \Clarkeash\Doorman\Exceptions\NotYourInviteCode
     */
    protected function validateInvite(Invite $invite, string $email = null)
    {
        if ($invite->max != 0 && $invite->uses >= $invite->max) {
            throw new MaxUsesReached(trans('doorman::messages.maxed', [ 'code' => $invite->code ]));
        }

        if (!is_null($invite->valid_until) && $invite->valid_until->isPast()) {
            throw new ExpiredInviteCode(trans('doorman::messages.expired', [ 'code' => $invite->code ]));
        }

        if (!is_null($invite->for) && $invite->for != $email) {
            throw new NotYourInviteCode(trans('doorman::messages.restricted', [ 'code' => $invite->code ]));
        }
    }
}

<?php

namespace Clarkeash\Doorman\Exceptions;

use Clarkeash\Doorman\Models\Invite;

class ExpiredInviteCode extends DoormanException
{
    const DATE_FORMAT = 'Y-m-d H:i';

    public static function forInvite(Invite $invite): ExpiredInviteCode
    {
        return new self(trans('doorman::messages.expired', [
            'code' => $invite->code,
            'expired' => $invite->expires()->format(self::DATE_FORMAT),
        ]));
    }
}

<?php

namespace Clarkeash\Doorman\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

interface InviteInterface
{
    public function setForAttribute($for);

    public function hasExpired();

    public function isFull();

    public function isRestricted();

    public function isRestrictedFor($email);

    public function isUseless();

    public function scopeExpired($query);

    public function scopeFull($query);

    public function scopeUseless($query);
}

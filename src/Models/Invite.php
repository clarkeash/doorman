<?php

namespace Clarkeash\Doorman\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $dates = [ 'valid_until' ];
    
    public function __construct(array $attributes = [])
    {
        $this->table = config('doorman.invite_table_name');
        parent::__construct($attributes);
    }

    public function hasExpired()
    {
        if(is_null($this->valid_until)) return false;

        return $this->valid_until->isPast();
    }

    public function isFull()
    {
        if($this->max == 0) return false;

        return $this->uses >= $this->max;
    }

    public function isRestricted()
    {
        return !is_null($this->for);
    }

    public function isRestrictedFor($email)
    {
        return $email == $this->for;
    }

    public function isUseless()
    {
        return $this->hasExpired() || $this->isFull();
    }
}

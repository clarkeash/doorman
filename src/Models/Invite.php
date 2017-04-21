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

    public function getExpiredAttribute()
    {
        if(is_null($this->valid_until)) return false;

        return $this->valid_until->isPast();
    }

    public function getFullAttribute()
    {
        if($this->max == 0) return false;

        return $this->uses >= $this->max;
    }
}

<?php

namespace Clarkeash\Doorman\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $dates = [ 'valid_until' ];
    
    public function __construct(array $attributes = [ ])
    {
        $this->table = config('doorman.invite_table_name');
        parent::__construct($attributes);
    }

    public function hasExpired()
    {
        if (is_null($this->valid_until)) return false;

        return $this->valid_until->isPast();
    }

    public function isFull()
    {
        if ($this->max == 0) return false;

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

    /**
     * Scope a query to only include expired invites.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', Carbon::now(config('app.timezone')));
    }

    /**
     * Scope a query to only include full invites.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFull($query)
    {
        return $query->where('max', '!=', 0)->whereRaw('uses = max');
    }

    /**
     * Scope a query to only include useless invites.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUseless($query)
    {
        return $query
            ->where(function($q) {
                $this->scopeExpired($q);
            })
            ->orWhere(function($q) {
                $this->scopeFull($q);
            })
        ;
    }
}

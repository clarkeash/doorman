<?php

namespace Clarkeash\Doorman\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Invite extends BaseInvite
{
    public function setForAttribute($for)
    {
        if (is_string($for)) {
            $this->attributes['for'] = strtolower($for);
        } else {
            $this->attributes['for'] = null;
        }
    }

    /**
     * Has the invite expired.
     *
     * @return bool
     */
    public function hasExpired(): bool
    {
        if (is_null($this->valid_until)) {
            return false;
        }

        return $this->valid_until->isPast();
    }

    /**
     * Is the invite full.
     *
     * @return bool
     */
    public function isFull(): bool
    {
        if ($this->max == 0) {
            return false;
        }

        return $this->uses >= $this->max;
    }

    /**
     * Is the invite restricted to a user.
     *
     * @return bool
     */
    public function isRestricted(): bool
    {
        return !is_null($this->for);
    }


    /**
     * Is the invite restricted for a particular user.
     *
     * @param string $email
     *
     * @return bool
     */
    public function isRestrictedFor($email): bool
    {
        return strtolower($email) == $this->for;
    }

    /**
     * Can the invite be used anymore.
     *
     * @return bool
     */
    public function isUseless(): bool
    {
        return $this->hasExpired() || $this->isFull();
    }

    /**
     * Scope a query to only include expired invites.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('valid_until', '<', Carbon::now(config('app.timezone')));
    }

    /**
     * Scope a query to only include full invites.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFull(Builder $query): Builder
    {
        return $query->where('max', '!=', 0)->whereRaw('uses = max');
    }

    /**
     * Scope a query to only include useless invites.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUseless(Builder $query): Builder
    {
        return $query
            ->where(function ($q) {
                $this->scopeExpired($q);
            })
            ->orWhere(function ($q) {
                $this->scopeFull($q);
            });
    }
}

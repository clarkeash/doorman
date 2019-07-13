<?php

namespace Clarkeash\Doorman\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseInvite
 * @package Clarkeash\Doorman\Models
 *
 * @property Carbon|null valid_until
 * @property int max
 * @property int uses
 * @property string|null for
 * @property string code
 * @mixin Builder
 */
abstract class BaseInvite extends Model
{
    protected $dates = ['valid_until'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('doorman.invite_table_name');
        parent::__construct($attributes);
    }

    public abstract function setForAttribute($for);

    public abstract function hasExpired(): bool;

    public abstract function isFull(): bool;

    public abstract function isRestricted(): bool;

    public abstract function isRestrictedFor($email): bool;

    public abstract function isUseless(): bool;

    public abstract function scopeExpired(Builder $query): Builder;

    public abstract function scopeFull(Builder $query): Builder;

    public abstract function scopeUseless(Builder $query): Builder;

}
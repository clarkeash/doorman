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
}

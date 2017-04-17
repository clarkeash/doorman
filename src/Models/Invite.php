<?php

namespace Clarkeash\Doorman\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $dates = [ 'valid_until' ];
    $this->setTable(config('doorman.invite_table_name'));
}

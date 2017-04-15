<?php

namespace Clarkeash\Doorman\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $table = config('doorman.invite_table_name');
    protected $dates = [ 'valid_until' ];
}

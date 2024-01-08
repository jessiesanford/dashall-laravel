<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignableShift extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $primaryKey = 'shift_id';
    protected $table = 'assignable_shifts';
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $primaryKey = 'sid';
    protected $table = 'settings';
}

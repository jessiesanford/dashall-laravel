<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $primaryKey = 'user_id';
    protected $table = 'drivers';

    public function user()
    {
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }

    public function shift()
    {
        return $this->hasMany('App\DriverShift', 'user_id', 'user_id');
    }

    public function order()
    {
        return $this->belongsToMany('App\Order', 'driver_id', 'user_id');
    }

    public function payroll()
    {
        return $this->hasMany('App\DriverPayroll', 'driver_id', 'user_id');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverPayroll extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $primaryKey = 'trans_id';
    protected $table = 'driver_payroll';

    public function driver()
    {
        return $this->belongsTo('App\Driver', 'user_id', 'user_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'order_id');
    }
}



<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverShift extends Model
{
    public $timestamps = false;
    public $incrementing = true;
    protected $primaryKey = 'shift_id';
    protected $table = 'driver_shifts';

    protected $fillable = ['req_unshift', 'start', 'end'];

    public function driver()
    {
        return $this->hasOne('App\Driver', 'user_id', 'user_id');
    }


    public function setReqUnshift($bool) {
        return $this->update(array('req_unshift' => $bool));
    }
}

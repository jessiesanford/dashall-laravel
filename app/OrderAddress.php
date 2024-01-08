<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'order_id';
    protected $table = 'order_addresses';

    public function order()
    {
        return $this->belongsTo('App\Order' ,'order_id');
    }
}

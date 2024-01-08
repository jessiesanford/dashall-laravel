<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderRating extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'order_id';
    protected $table = 'order_ratings';

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}

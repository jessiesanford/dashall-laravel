<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderMeta extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'order_id';
    protected $table = 'order_meta';

    public function order()
    {
        $this->belongsTo('App\Order', 'order_id', 'order_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeCharge extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'charge_id';
    protected $table = 'stripe_charges';

    public function order() {
        $this->belongsTo('App\Order');
    }
}

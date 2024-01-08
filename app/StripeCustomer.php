<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeCustomer extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'stripe_id';
    protected $table = 'stripe_customers';

    public function user() {
        $this->belongsTo('App\User', 'user_id', 'user_id');
    }

}

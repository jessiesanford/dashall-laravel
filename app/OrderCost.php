<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCost extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'order_id';
    protected $table = 'order_costs';

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }


    public function setPayAuth($bool)
    {
        return $this->setAttribute('pay_auth', $bool);
    }

    public function setPayCapture($bool)
    {
        return $this->setAttribute('pay_capture', $bool);
    }

    public function setAmount($amount)
    {
        return $this->setAttribute('amount', $amount);
    }

    public function setMargin($amount)
    {
        return $this->setAttribute('margin', $amount);
    }

//    public function setStripeMargin($amount)
//    {
//        return $this->setAttribute('stripe_margin', $amount);
//    }

    public function setDeliveryFee($amount)
    {
        return $this->setAttribute('delivery_fee', $amount);
    }

    public function setTip($amount)
    {
        return $this->setAttribute('tip', $amount);
    }

    public function setDiscountAmount($amount)
    {
        return $this->setAttribute('discount_amount', $amount);
    }


}

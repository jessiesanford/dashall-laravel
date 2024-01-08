<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use DateTime;

class Order extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'order_id';

    protected $fillable = [ 'user_id', 'status', 'summary', 'location', 'active', 'init_time' ];

    public function user()
    {
        return $this->belongsTo('App\User' , 'user_id', 'user_id');
    }

    public function driver()
    {
        return $this->hasOne('App\Driver', 'user_id', 'driver_id');
    }

    public function address()
    {
        return $this->hasOne('App\OrderAddress', 'order_id', 'order_id');
    }

    public function status()
    {
        return $this->hasOne('App\OrderStatus', 'status_id', 'status_id');
    }

    public function cost() {
        return $this->hasOne('App\OrderCost', 'order_id', 'order_id');
    }

    public function rating() {
        return $this->hasOne('App\OrderRating', 'order_id', 'order_id');
    }

    public function meta() {
        return $this->hasOne('App\OrderMeta', 'order_id', 'order_id');
    }

    public function promo() {
        return $this->hasOne('App\Promo', 'promo_code', 'promo');
    }

    public function payroll()
    {
        return $this->hasOne('App\DriverPayroll', 'driver_id', 'driver_id');
    }

    public function stripeCharge() {
        return $this->hasOne('App\StripeCharge');
    }







    public function totalAmount() {
        return number_format((($this->cost['amount'] - $this->cost['discount_amount'] * $this->cost['margin']) + $this->cost['delivery_fee'] + $this->cost['tip']) * $this->cost['stripe_margin'], 2);
    }

    public function calcHumanTime() {
        $datetime = $this->attributes['init_time'];
        $full = false;

        $now = new DateTime;
        $ago = new DateTime($datetime);

        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';

    }

    public function elapsed() {
        $t1 = new DateTime($this->init_time);
        $t2 = new DateTime($this->complete_time);
        $interval = $t1->diff($t2);
        return $interval->format('%h h %i m %S s');
    }



    // Setters

    public function setStatus($status)
    {
        return $this->setAttribute('status_id', $status);
    }



    public function setDriver($driver_id)
    {
        return $this->setAttribute('driver_id', $driver_id);
    }



    public function setActive($bool)
    {
        return $this->setAttribute('active', $bool);
    }


    public function setSummary($summary)
    {
        return $this->setAttribute('summary', $summary);
    }



    public function setLocation($location)
    {
        return $this->setAttribute('location', $location);
    }

    public function setPromo($code)
    {
        return $this->setPromo('promo', $code);
    }


    public function parseOrderSummary()
    {
        $summary = $this->summary;
        $r = preg_split("/\\r\\n|\\r|\\n/", $summary);
        if (sizeof($r) == 1 && end($r) == "") {
            return [];
        } else {
            return $r;
        }
    }




}

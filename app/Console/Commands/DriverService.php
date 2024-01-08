<?php

namespace App\Console\Commands;

use App\DriverShift;
use App\OrderAddress;
use App\OrderCost;
use Auth;
use App\Order;
use App\Promo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DriverService
{
    public function editOrderCosts(Order $order, Request $req)
    {
        $order->cost()->update(array(
            'margin' => $req->margin / 100 + 1,
            'delivery_fee' => $req->delivery_fee,
            'discount_amount' => $req->discount
        ));

        $return_arr['alert'] = "The order costs have been updated.";
        return $return_arr;
    }


    public function takeShift($user_id, $start, $end)
    {
        $shift = new DriverShift();
        $shift->user_id = $user_id;
        $shift->start = $start;
        $shift->end = $end;
        $shift->save();
    }

}
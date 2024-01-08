<?php

namespace App\Console\Commands;

use App\OrderAddress;
use App\OrderCost;
use App\OrderMeta;
use Auth;
use App\Order;
use App\Promo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderService
{
    // put this somewhere else bud
    public function isCurrency($val)
    {
        return preg_match("/^-?[0-9]+(?:\.[0-9]{2})?$/", $val);
    }

    public function createOrder($user_id, $location)
    {
        $order = new Order;
        $order->user_id = $user_id;
        $order->status_id = 'AWD_S1';
        $order->location = $location;
        $order->active = 1;
        $order->init_time = Carbon::now();
        $order->save();

        if ($order->save()) {
            $address = new OrderAddress();
            $address->user_id = $user_id;
            $order->address()->save($address);

            $cost = new OrderCost();
            $cost->margin = 1.1;
            $cost->stripe_margin = 1.03;
            $cost->delivery_fee = 7.00; // global variable for delivery fee should go here
            $order->cost()->save($cost);

            $this->createOrderMeta($order);
        }
    }

    public function createOrderMeta(Order $order)
    {
        $meta = new OrderMeta();
        $order->meta()->save($meta);
    }



    public function updateOrderAddress(Order $order, Request $req) {
        $order->address()->update(array(
            'street' => $req->address_street,
            'city' => $req->address_city,
            'province' => "NL"
        ));
    }


    public function updateOrderSummary(Order $order, $summary) {
        $order->update(array(
            'summary' => $summary,
        ));
    }


    public function updateOrderLocation(Order $order, $location) {
        $order->update(array(
            'location' => $location,
        ));
    }



    public function validatePromo(Order $order, $promo_method, $promo_data) {
        $user = $order->user()->first();
        $dashcash_balance = $user->dashcash_balance;
        $order_counter = Order::where('user_id', $user->user_id)->where('status_id', 'ARCH')->count();

        if ($promo_method == 'competition_code')
        {
            $this->updateOrderStatus($order, 'AWD_S3');
            $this->updateOrderMetaCompCode($order, $promo_data);
            $this->alerts[] = "Competition Code Added: " . $promo_data;
        }
        if ($promo_method == 'coupon_redeem')
        {
            $promo = Promo::where('promo_code', $promo_data)->first();

            if ($promo)
            {
                if ($promo->promo_code == "FIRSTDASH" && $order_counter == 0 || $promo->promo_code == "MUN16" && $order_counter == 0)
                {
                    $this->updateOrderStatus($order, 'AWD_S3');
                    $this->applyPromo($order, $promo->promo_code);
                    $this->applyDiscount($order, 5.00);
                    $this->alerts[] = "Promotion Added: " . $promo->promo_desc;
                }
                else
                {
                    $return_arr['error'] = true;
                    $this->alerts[] = "You are not eligible for this coupon or it has expired.";
                }
            }
            else
            {
                $return_arr['form_check'] = "error";
                $this->alerts[] = "Coupon does not exist.";
            }

        }
        else if ($promo_method == 'dashcash_redeem')
        {
            if ($this->isCurrency($promo_data) == false || $promo_data < 0) {
                $return_arr['error'] = true;
                $this->alerts[] = "That is not a valid cash amount.";
            }
            else if ($promo_data == 0 || $promo_data > $dashcash_balance)
            {
                $return_arr['error'] = true;
                $this->alerts[] = "This amount exceeds your DashCash balance.";
            }
            else if ($promo_data > 25.00)
            {
                $return_arr['error'] = true;
                $this->alerts[] = "Maximum redeemable amount per order is $25.";
            }
            else
            {
                $this->applyPromo($order, 'DASHCASH');
                $this->applyDiscount($order, $promo_data);
                $this->alerts[] = 'Cool, we will apply $'. $promo_data .' DashCash towards your order.';
            }
        }
        else
        {
            $this->alerts[] = "We've updated your order.";
        }

        $return_arr['alert'] = $this->alerts[0];
        return $return_arr;
    }

    public function applyPromo(Order $order, $promo_code)
    {
        $order->promo = $promo_code;
        $order->save();
    }

    public function applyDiscount(Order $order, $discount)
    {
        $order->cost()->update(array(
            'discount_amount' => $discount
        ));
    }



    public function updateOrderPayment() {

    }



    public function cancelOrder(Order $order, $cancel_reason)
    {
        $this->updateOrderStatus($order, 'CANC');
        $this->setOrderActive($order, 0);
        $this->updateOrderMetaCancelReason($order, $cancel_reason);
    }



    public function updateOrderStatus(Order $order, $status)
    {
        $order::where('order_id', $order->order_id)->update(['status_id' => $status]);
    }



    public function setOrderActive(Order $order, $bool)
    {
        $order::where('order_id', $order->order_id)->update(['active' => $bool]);
    }



    public function authOrderPayment(Order $order, $bool)
    {
        $order->cost()->update(['pay_auth' => $bool]);
    }



    public function captureOrderPayment(Order $order, $bool)
    {
        $order->cost()->update(['capture_payment' => $bool]);
    }



    public function deleteCreditCard() {

    }

    public function updateOrderMetaCompCode(Order $order, $code)
    {
        $order->meta()->update(['competition_code' => $code]);
    }

    public function updateOrderMetaCancelReason(Order $order, $reason)
    {
        $order->meta()->update(['cancel_reason' => $reason]);
    }


}



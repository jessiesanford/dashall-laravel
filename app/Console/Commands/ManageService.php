<?php

namespace App\Console\Commands;

use App\OrderAddress;
use App\OrderCost;
use App\OrderStatus;
use Auth;
use App\Order;
use App\Promo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManageService
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



    public function editOrderInfo(Order $order, Request $req)
    {
        $order->update(array(
            'summary' => $req->summary,
            'location' => $req->location,
        ));

        $order->address()->update(array(
            'street' => $req->street
        ));

        $return_arr['alert'] = "The order details been updated.";
        return $return_arr;
    }



    public function editOrderStatus(Order $order, Request $req)
    {
        $statusList = OrderStatus::all();
        $currentStatus = $order['status_id'];
        $newStatus = $req->status_id;

        // check to make sure it's a valid status first
        if ($statusList->contains('status_id', $req->status_id)) {
            if ($currentStatus == 'ARCH') {
                $return_arr['alert'] = "Cannot change a completed archived order";
            }
            else if ($currentStatus == 'COM' && $newStatus != 'ARCH') {
                $return_arr['alert'] = "Completed orders must be archived.";
            }
            else {
                $order->setStatus($req->status_id);
                $order->save();
                $return_arr['alert'] = "The order status has been updated.";
                $return_arr['alert'] = "Order updated to " . $req->status_id;
            }
        }
        else {
            $return_arr['alert'] = "Invalid order status change.";
        }

        return $return_arr;
    }



    public function deleteOrder($order)
    {
        try
        {
            Order::destroy($order->order_id);
            $this->response = "This order has been deleted.";
        }
        catch(\Illuminate\Database\QueryException $ex){
            $this->response = $ex->getMessage();
        }

//        $return_arr['alert'] = $this->response;

//        echo json_encode($return_arr);
    }
}
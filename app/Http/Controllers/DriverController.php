<?php

namespace App\Http\Controllers;

use App\Driver;
use Illuminate\Http\Request;

use Auth;
use Session;

use App\Order;
use App\Console\Commands\OrderService;
use App\Console\Commands\DriverService;

class DriverController extends Controller
{
    public $return_arr = [];
    public $alerts = [];

    public $orderService;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->orderService = new OrderService();
        $this->driverService = new DriverService();
    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('driver', [
            'title' => 'Driver',

            'assigned_orders' => Order::with('status', 'user', 'driver', 'address')
                ->where('driver_id', Auth::user()->user_id)
                ->where('active', 1)
                ->orderBy('order_id', 'desc')->paginate(10),

            'count_unassigned_orders' => Order::with('status', 'user', 'driver', 'address')->where('status_id', 'APP_S1')->where('driver_id', null)->count(),

            'unassigned_orders' => Order::with('status', 'user', 'driver', 'address')->where('status_id', 'APP_S1')->where('driver_id', null)->orderBy('order_id', 'desc')->paginate(10)
        ]);
    }



    public function selfAssignOrder(Request $req)
    {
        $order = Order::find($req->order_id);
        $order->setDriver(Auth::user()->user_id);
        $order->setStatus('APP_S2');
        $order->save();

        $return_arr['alert'] = "This order has been assigned to you.";
        echo json_encode($return_arr);
    }


    public function updateOrderCost(Request $req)
    {
        $order = Order::find($req->order_id);
        $order->cost->setAmount($req->order_cost);
        $order->cost->save();
        $order->setStatus('APP_S3');
        $order->save();

        $return_arr['alert'] = "Order #" . $req->order_cost . " cost has been updated";
        echo json_encode($return_arr);
    }

    public function sendArrivalStatus(Request $req)
    {
        $order = Order::find($req->order_id);

        if (empty($order)) {
            $return_arr['alert'] = 'Order not found.';
        }
        else {
            if ($order->status_id == 'APP_S3') {
                $order->setStatus('ARR');
                $order->save();
                $return_arr['alert'] = "Order updated.";
            }
            else {
                $return_arr['alert'] = "You can't set this order to arrived status.";
            }
        }

        echo json_encode($return_arr);
    }

    public function markComplete(Request $req)
    {
        $order = Order::find($req->order_id);

        if (empty($order)) {
            $return_arr['alert'] = 'Order not found.';
        }
        else {
            if ($order->status_id == 'ARR') {
                $order->setStatus('COM');
                $order->save();
                $return_arr['alert'] = "Order updated.";
            }
            else {
                $return_arr['alert'] = "Order could not be completed.";
            }
        }

        echo json_encode($return_arr);
    }

}

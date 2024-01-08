<?php

namespace App\Http\Controllers;

use App\Driver;
use App\OrderStatus;
use Illuminate\Http\Request;

use Auth;
use Session;

use App\Order;
use App\Console\Commands\OrderService;
use App\Console\Commands\ManageService;

use Illuminate\Support\Facades\Input;

class ManageController extends Controller
{
    public $return_arr = [];
    public $alerts = [];
    public $response = "";
    public $error = "";

    public $orderService;
    public $stripeCustomer;



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
        $this->manageService = new ManageService();
    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
//        dd(['COM']);
//          dd($req->filterStatusList);

        if (empty($req->filterStatusList)) {
            $filter = OrderStatus::getAllStatusIDs();
        }
        else {
            $filter = OrderStatus::pluckStatusIDs($req->filterStatusList);
        }


//        if (empty($filter)) {
//            $filter = OrderStatus::getAllStatusIDs();
//        }

        return view('manage', [
            'title' => 'Manage Orders',
            'filter' => OrderStatus::getAllStatusIDs(),
            'drivers' => Driver::with('user')->get(),
            'orders' => Order::with('status', 'user', 'driver', 'address')->whereIn('status_id', $filter)->orderBy('order_id', 'desc')->paginate(5)
        ]);
    }


    public function deleteOrder(Request $req)
    {
        $order = Order::find($req->order_id);

        // don't delete completed or archived orders.
        if ($order->status['rank'] == 99) {
            $this->response = 'You cannot delete an archived order.';
        }
        else
        {
            try
            {
                Order::destroy($req->order_id);
                $this->response = "This order has been deleted.";
            }
            catch(\Illuminate\Database\QueryException $ex){
                $this->response = $ex->getMessage();
            }
        }

        $return_arr['alert'] = $this->response;

        echo json_encode($return_arr);
    }



    public function assignDriver(Request $req)
    {
        $order = Order::find($req->order_id);
        $order->setDriver($req->driver_id);
        $order->setStatus('APP_S2');
        $order->save();

        $driver = Driver::where('user_id', $req->driver_id)->first();

        $return_arr['alert'] = $driver->user->first_name . " " . $driver->user->last_name . " has been assigned to this order.";
        echo json_encode($return_arr);
    }



    public function unassignDriver(Request $req)
    {
        $order = Order::find($req->order_id);
        $order->setDriver(null);
        $order->setStatus('APP');
        $order->save();

        $return_arr['alert'] = "The driver has been unassigned from this order.";
        echo json_encode($return_arr);
    }


    public function updateOrderCosts(Request $req)
    {
        $order = Order::find($req->order_id);
        $response = $this->manageService->editOrderCosts($order, $req);

        echo json_encode($response);
    }


    public function updateOrderInfo(Request $req)
    {
        $order = Order::find($req->order_id);
        $response = $this->manageService->editOrderInfo($order, $req);

        echo json_encode($response);
    }


    public function updateOrderStatus(Request $req)
    {
        $order = Order::find($req->order_id);
        $response = $this->manageService->editOrderStatus($order, $req);

        echo json_encode($response);
    }

}

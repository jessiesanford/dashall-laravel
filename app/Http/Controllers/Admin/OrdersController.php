<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\ManageService;
use App\Console\Commands\OrderService;
use App\DriverShift;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use App\Driver;
use Carbon\Carbon;
use App\Order;
use App\User;

class OrdersController extends Controller
{
    public $return_arr = [];
    public $alerts = [];

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
    public function index()
    {
        return view('admin/orders', [
            'title' => 'Orders',
        ]);
    }


    public function getOrdersInRange(Request $req)
    {
        $return_arr = $this->getOrderStats($req->start_date, $req->end_date);

//        $return_arr['trans_stats'] = $trans_model['trans_stats'];
//        $return_arr['trans'] = $trans_model['trans'];
        echo json_encode($return_arr);
    }



    public function getOrderStats($from_date = '0000-00-00', $to_date = '9999-12-31', $limit = 100)
    {
//        error_log($from_date);

        $orders = Order::with('status', 'user', 'driver', 'rating', 'meta', 'address', 'cost')
            ->whereBetween('init_time', [$from_date, $to_date])
            ->orderBy('init_time', 'desc')->get();

        $repeat_customer_data = DB::select(
        	"SELECT COUNT(*) as count, SUM(counter) as sum FROM (
				SELECT orders.user_id, COUNT(*) AS counter FROM orders
				WHERE orders.init_time BETWEEN CAST('". $from_date ."' AS DATE) AND CAST('". $to_date ."' AS DATE)
			    GROUP BY orders.user_id
			    HAVING COUNT(*) > 1
			) AS T"
		)[0];

        $total_orders = DB::select("
            SELECT COUNT(*) AS total FROM orders
            WHERE orders.init_time BETWEEN CAST('". $from_date ."' AS DATE) AND CAST('". $to_date ."' AS DATE)
        ")[0];

        $total_complete_orders = DB::select("
            SELECT COUNT(*) AS total FROM orders
            WHERE orders.init_time BETWEEN CAST('". $from_date ."' AS DATE) AND CAST('". $to_date ."' AS DATE)
            AND (orders.status_id = 'ARCH' OR orders.status_id = 'COM')
        ")[0];

        $avg_order_time = DB::select("
			SELECT AVG(TIMESTAMPDIFF(MINUTE, orders.init_time, orders.complete_time)) as avg_time
			FROM orders
			WHERE orders.init_time BETWEEN CAST('". $from_date ."' AS DATE) AND CAST('". $to_date ."' AS DATE) AND orders.status_id = 'ARCH'
			AND TIMESTAMPDIFF(MINUTE, orders.init_time, orders.complete_time) > 0
			AND TIMESTAMPDIFF(MINUTE, orders.init_time, orders.complete_time) < 120
        ")[0];

        $hot_hours = DB::select("
			SELECT hour(init_time) as hour, count(*) as count
			FROM orders
            WHERE orders.init_time BETWEEN CAST('". $from_date ."' AS DATE) AND CAST('". $to_date ."' AS DATE)
			AND orders.status_id = 'ARCH'
			GROUP BY hour(init_time)
		");


        $hot_hours_arr = array();
        foreach ($hot_hours as $hot_hour)
        {
            $new_row = array();

            if ((int)$hot_hour->hour == 0)
            {
                $new_row['hour'] = "24";
            }
            else {
                $new_row['hour'] = $hot_hour->hour;
            }

            $new_row['counter'] = $hot_hour->count;
            $hot_hours_arr[] = $new_row;
        }

        $order_stats = array(
            'total_orders' => $total_orders->total,
            'total_complete_orders' => $total_complete_orders->total,
            'avg_order_time' => number_format($avg_order_time->avg_time, 2),
            'repeat_customer_data' => $repeat_customer_data,
            'hot_hours' => $hot_hours_arr
        );

        $return_array = array(
            'orders' => $orders,
            'order_stats' => $order_stats
        );

        return $return_array;
    }


    public function createTestOrder()
    {
        $testUsers = User::whereIn('email', ['test1@dashall.ca', 'test2@dashall.ca', 'test3@dashall.ca'])->get();

        foreach($testUsers as $user) {
            $order = Order::where('user_id', $user->user_id)->where('active', 1)->first();
            if ($order) {
                $this->manageService->deleteOrder($order);
            }
            $this->orderService->createOrder($user->user_id, 'Test Order for ' . $user->email);
        }
    }

}

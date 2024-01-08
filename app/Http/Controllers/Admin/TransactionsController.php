<?php

namespace App\Http\Controllers\Admin;

use App\DriverShift;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use App\Driver;
use Carbon\Carbon;
use App\Order;

class TransactionsController extends Controller
{
    public $return_arr = [];
    public $alerts = [];

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/transactions', [
            'title' => 'Transactions',
        ]);
    }

    public function getTransactionsInRange(Request $req)
    {
        $trans_model = $this->getTransactionStats($req->start_date, $req->end_date);

        $return_arr['trans_stats'] = $trans_model['trans_stats'];
        $return_arr['trans'] = $trans_model['trans'];
        echo json_encode($return_arr);
    }

    public function getTransactionStats($from_date = '0000-00-00', $to_date = '9999-12-31', $limit = 100)
    {
        $transactions = Order::with('status', 'user', 'driver', 'rating', 'meta', 'address', 'cost')
            ->whereBetween('init_time', [$from_date, $to_date])
            ->where('status_id', 'ARCH')
            ->orderBy('init_time', 'desc')->get();

        $transaction_count = Order::with('status', 'user', 'driver', 'rating', 'meta', 'address', 'cost')
            ->whereBetween('init_time', [$from_date, $to_date])
            ->where('status_id', 'ARCH')
            ->orderBy('init_time', 'desc')->count();

        $revenue = 0.00;
        $profit = 0.00;
        $promo_count = 0;
        $dashcash_count = 0;
        $repeat_customer_orders = 0;
        $trans_arr = array();

        if ($transaction_count == 0) {
            return;
        }

        foreach ($transactions as $order)
        {
            if ($order->cost['stripe_margin'] == 0.00) {
                $stripe_margin = 1;
            }
            else {
                $stripe_margin = $order->cost['stripe_margin'];
            }

            // for global shit
            $charged = number_format((($order->cost['amount'] * $order->cost['margin']) + $order->cost['delivery_fee']) * $stripe_margin, 2);
            $stripe_cut = number_format(($charged + $order->cost['tip']) * 0.029 + 0.3, 2);
            $order_profit = $charged - $order->cost['amount'] - $order->cost['delivery_fee'] - $stripe_cut;

            $revenue += number_format(($order->cost['amount'] * 1.1 + $order->cost['delivery_fee'] + $order->cost['tip']) * 1.03, 2);
            $profit += number_format($charged - (($order->cost['delivery_fee'] * 0.9) + $order->cost['amount'] + $stripe_cut), 2);

            if ($order['promo'] != '')
            {
                if ($order['promo'] == 'FIRSTDASH') {
                    $promo_count++;
                }
                else if ($order['promo'] == 'DASHCASH') {
                    $dashcash_count++;
                }

                $profit -= $order->cost['discount_amount'];
                $order_profit -= $order->cost['discount_amount'];
            }
            else if ($order->cost['delivery_fee'] != 7.00)
            {
                $profit = number_format($profit + ($order->cost['delivery_fee'] - 6.30), 2);
            }

            $repeat_customer = 0;
            // call to get customers repeat orders here

            if ($repeat_customer > 1)
            {
                $order['repeat_customer'] = $repeat_customer;
                $repeat_customer_orders++;
            }

            $order['stripe_cut'] = $stripe_cut;
            $order['revenue'] = $revenue;
            $order['profit'] = number_format($order_profit, 2);

            $trans_arr[] = $order;
        }

        $avg_order_cost = number_format(0, 2);
        // call to get avg order cost here

        $avg_order_profit = number_format($profit / $transaction_count, 2);

        $trans_stats = array(
            'order_count' => $transaction_count,
            'revenue' => $revenue,
            'profit' => $profit,
            'avg_order_cost' => $avg_order_cost,
            'avg_order_profit' => $avg_order_profit,
            'promo_count' => $promo_count,
            'dashcash_count' => $dashcash_count,
            'repeat_customer_orders' => $repeat_customer_orders
        );


        $return_array = array(
            'trans' => $trans_arr,
            'trans_stats' => $trans_stats
        );

        return $return_array;
    }

}

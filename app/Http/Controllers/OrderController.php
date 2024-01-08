<?php

namespace App\Http\Controllers;

use App\OrderStatus;
use App\StripeCustomer;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Order;
use App\Console\Commands\OrderService;
use App\Console\Commands\StripeService;
use Illuminate\Support\Facades\Input;
use Stripe\Stripe;

use Symfony\Component\Console\Output\ConsoleOutput;

class OrderController extends Controller
{
    public $return_arr = [];
    public $alerts = [];

    public $orderService;
    public $stripeCustomer;
    public $activeOrder;

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
        $this->stripeService = new StripeService();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $processingStatusList = OrderStatus::where('rank', '>=', 0)->get()->sortBy('rank');

        $this->activeOrder = $this->getActiveOrder();
        $this->stripeCustomer = $this->stripeService->getStripeCustomer(Auth::user());

        return view('order', [
            'title' => 'Order',
            'order' => $this->activeOrder,
            'processingStatusList' => $processingStatusList,
            'stripeCustomer' => $this->stripeCustomer
        ]);
    }

    public function getActiveOrder()
    {
        return Order::where('user_id', Auth::user()->user_id)->where('active', 1)->first();
    }



    public function stepBack()
    {
        $order = $this->getActiveOrder();
        $orderStatusID = $order->status->status_id;

        $changableStatusList = [
            'AWD_S1',
            'AWD_S2',
            'AWD_S3',
            'AWD_S4',
        ];

        foreach($changableStatusList as $key => $status) {
            if ($orderStatusID == $status && $key >= 1) {
                $newStatus = $changableStatusList[$key - 1];
                $order->setStatus($newStatus);
                $order->save();
            }
        }

        $this->return_arr['alert'] = $order->status->summary;

        $response = array(
          'alert' => $this->return_arr['alert']
        );

        return response()->json($response);


    }

    public function create(Request $req)
    {


        if (empty($req['dashbox_location']))
        {
            $return_arr['form_check'] = "error";
            $return_arr['error_source'] = "dashbox_location";
            $this->alerts[] = "Please specify a location/establishment you want delivery from.";
        }
//        else if (empty($req['dashbox_summary']))
//        {
//            $return_arr['form_check'] = "error";
//            $return_arr['error_source'] = "dashbox_desc";
//            $this->alerts[] = "Please specify what you would like delivered.";
//        }
        else
        {
            $this->orderService->createOrder(Auth::user()->user_id, $req->dashbox_location);
            $this->alerts[] = "Order Created.";
        }

        $return_arr['alert'] = $this->alerts[0];
        echo json_encode($return_arr);
    }

    public function cancel(Request $req)
    {
        $order = $this::getActiveOrder();
        $this->orderService->cancelOrder($order, $req->cancel_reason);

        $return_arr['alert'] = "Your order has been cancelled.";
        echo json_encode($return_arr);
    }


    public function submitDetails(Request $req)
    {
        $order = $this::getActiveOrder();
//        $input = Input::except('_token', 'order_location');
        $items = Input::get('order_items');
        $summary = '';

        foreach ($items as $key => $value)
        {

            // if it's the last value, dont break line
            if ($value == '') {
                $summary .= '';
            }
//            else if (end($input) == $value) {
//                $summary .= $value;
//            }
            else {
                $summary .= $value . "\n";
            }
        }

//        dd($summary);

        if (!$summary) {
            $return_arr['alert'] = "Please provide items for your order.";
            $return_arr['error'] = true;
        }
        else {
            $this->orderService->updateOrderSummary($order, $summary);
            $this->orderService->updateOrderLocation($order, $req->order_location);
            $this->orderService->updateOrderStatus($order, 'AWD_S2');
            $return_arr['alert'] = 'We have updated your order.';
        }

        echo json_encode($return_arr);
    }


    public function submitAddress(Request $req)
    {
        $order = $this::getActiveOrder();

        if (!$req->address_street) {
            $return_arr['alert'] = "Please provide an address.";
            $return_arr['error'] = true;
        }
        else {
            $this->orderService->updateOrderAddress($order, $req);
            $this->orderService->updateOrderStatus($order, 'AWD_S3');
            $return_arr['alert'] = "Your address has been updated.";
        }

        echo json_encode($return_arr);
    }

    public function applyOrderPromo(Request $req)
    {
        $order = $this::getActiveOrder();
        $response = $this->alerts[] = $this->orderService->validatePromo($order, $req->promo_method, $req->promo_data);

        if (empty($response['error'])) {
            $this->orderService->updateOrderStatus($order, 'AWD_S4');
        }

        $return_arr = $response;
        echo json_encode($return_arr);
    }

    public function validateCreditCard(Request $req)
    {
        $order = $this::getActiveOrder();
        $user = $order->user()->first();
        $response = $this->stripeService->createStripeCustomer($user, $req);

        $return_arr['alert'] = $response['alert'];
        echo json_encode($return_arr);
    }

    public function authCreditCard(Request $req)
    {
        $order = $this::getActiveOrder();
        $user = $order->user()->first();
        $response = $this->stripeService->verifyCustomer($user);

        if (empty($response['error'])) {
            $this->orderService->updateOrderStatus($order, 'PDR');
            $this->orderService->authOrderPayment($order, 1);
            // TODO: delegate order to driver/manage
        }

        $return_arr['alert'] = $response['alert'];
        echo json_encode($return_arr);
    }

    public function deleteCreditCard(Request $req)
    {
        $order = $this::getActiveOrder();
        $user = $order->user()->first();
        $this->stripeService->deleteStripeCustomer($user);
        $this->alerts[] = "Your credit card information has been removed.";
        $return_arr['alert'] = $this->alerts[0];
        echo json_encode($return_arr);
    }

    public function calcSurgeFactor() {
        return 1;
    }

    public function submitOrderFeedback(Request $req)
    {
        $order = $this::getActiveOrder();

        if ($order->cost['pay_capture'] == 0)
        {
            $orderTotal = $order->totalAmount();
            $stripeCall = $this->stripeService->chargeStripeCustomer(Auth::user(), $order, $orderTotal);

            if ($stripeCall['error'] == true)
            {
                $return_arr['alert'] = $stripeCall['alert'];
                echo json_encode($return_arr);
            }
            else
            {
                $order->cost->setPayCapture(1);
                $order->setActive(0);
                $order->setStatus('ARCH');
                $order->save();

//                mysql_query("
//					UPDATE driver_payroll
//					SET tip = ". mysql_real_escape_string($order['tip']) ."
//					WHERE order_id = " . mysql_real_escape_string($order_id) . "
//				");

//                // check for first order, if first give referral credit
//                $sql = mysql_query("SELECT COUNT(*) as order_count FROM orders WHERE order_user = ". $order['order_user'] ."");
//                $order_count = mysql_fetch_assoc($sql);
//
//                if ($order_count['order_count'] == 1)
//                {
//                    self::give_referral_credit($order['order_user']);
//                }
//
//                if ($order['promo'] == "DASHCASH")
//                {
//                    DashCash::add_funds($order['order_user'], (-1 * $order['discount_amount']), "Redeemed DashCash for order.");
//                }

                $return_arr['alert'] = "Your order review has been submitted, thank you!";
                echo json_encode($return_arr);
            }
        }

        else
        {
            $return_arr['alert'] = 'Error';
            echo json_encode($return_arr);
        }
    }
}

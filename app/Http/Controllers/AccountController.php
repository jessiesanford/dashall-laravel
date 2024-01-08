<?php

namespace App\Http\Controllers;

use App\StripeCustomer;
use User;
use Hash;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Console\Commands\StripeService;

class AccountController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->stripeService = new StripeService();
    }

    public function index()
    {
        $this->stripeCustomer = $this->stripeService->getStripeCustomer(Auth::user());

        return view('user/account', [
            'title' => 'Account',
            'user' => Auth::user(),
            'stripeCustomer' => $this->stripeCustomer
        ]);
    }

    public function updateInfo(Request $req)
    {
        $user =  Auth::user();

        $validator = Validator::make($req->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
//            'address' => 'required',
        ]);


        if ($validator->fails()) {
            $return_arr['error'] = true;
            $return_arr['invalid_inputs'] = $validator->errors()->messages();
            $return_arr['alert'] = $validator->errors()->all()[0];
            echo json_encode($return_arr);
        }
        else {
            $user->update(array(
                'first_name' => $req->first_name,
                'last_name' => $req->last_name,
            ));



            $user->address()->update(array(
                'street' => $req->address['street_number'] . ' ' . $req->address['route'],
                'city' => $req->address['locality'],
                'province' => $req->address['administrative_area_level_1'],
                'country' => $req->address['country'],
                'postal_code' => $req->address['postal_code'],
            ));


            $user->save();

            $return_arr['alert'] = "Your information has been updated.";
            echo json_encode($return_arr);
        }
    }

    public function changeEmail(Request $req)
    {
        $user =  Auth::user();
        $email = $req->email;
        $password = $req->password;


        $validator = Validator::make($req->all(), [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required'
        ]);

        if (!(Hash::check($password, $user->password))) {
            $return_arr['error'] = true;
            $return_arr['alert'] = 'Incorrect Password';
            $return_arr['invalid_inputs'] = (object) array('password' => 'Incorrect Password');
            echo json_encode($return_arr);
        }

        else if($validator->fails())
        {
            $return_arr['error'] = true;
            $return_arr['invalid_inputs'] = $validator->errors()->messages();
            $return_arr['alert'] = $validator->errors()->all()[0];
            echo json_encode($return_arr);
        }

        else {
            $user->update(array(
                'email' => $email,
            ));

            $user->save();

            $return_arr['alert'] = "Your email address has been updated.";
            echo json_encode($return_arr);
        }
    }

    public function changePhone(Request $req)
    {
        $user =  Auth::user();
        $phone = $req->phone;
        $password = $req->password;

        $validator = Validator::make($req->all(), [
            'phone' => 'required|min:10|unique:users',
            'password' => 'required'
        ]);

        if (!(Hash::check($password, $user->password))) {
            $return_arr['error'] = true;
            $return_arr['alert'] = 'Incorrect Password';
            $return_arr['invalid_inputs'] = (object) array('password' => 'Incorrect Password');
            echo json_encode($return_arr);
        }

        else if($validator->fails())
        {
            $return_arr['error'] = true;
            $return_arr['invalid_inputs'] = $validator->errors()->messages();
            $return_arr['alert'] = $validator->errors()->all()[0];
            echo json_encode($return_arr);
        }

        else {
            $user->update(array(
                'phone' => $req->phone,
            ));

            $user->save();

            $return_arr['alert'] = "Your phone number has been updated.";
            echo json_encode($return_arr);
        }
    }

    public function changePassword(Request $req)
    {
        $user = Auth::user();

        $password = $req->current_password;
        $new_password = $req->password;
        $new_password_confirmation = $req->password_confirmation;

        $validator = Validator::make($req->all(), [
            'current_password' => 'required',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            $return_arr['error'] = true;
            $return_arr['invalid_inputs'] = $validator->errors()->messages();
            $return_arr['alert'] = $validator->errors()->all()[0];
            echo json_encode($return_arr);
        }
        else if (!(Hash::check($password, $user->password))) {
            $return_arr['error'] = true;
            $return_arr['alert'] = 'Incorrect Password';
            $return_arr['invalid_inputs'] = (object) array('password' => 'Incorrect Password');
            echo json_encode($return_arr);
        }
        else {
            $user->password = Hash::make($new_password);
            $user->save();

            $return_arr['alert'] = "Your password has been updated.";
            echo json_encode($return_arr);
        }
    }

    public function removePaymentMethod(Request $req)
    {
        $user = Auth::user();
        $stripe_id = StripeCustomer::where('user_id', $user->user_id)->first()->stripe_id;

//        // don't delete completed or archived orders.
//        if ($order->status['rank'] == 99) {
//            $this->response = 'You cannot delete an archived order.';
//        }
//        else
//        {
            try
            {
                StripeCustomer::destroy($stripe_id);
                $this->response = "Payment method has been deleted.";
            }
            catch(\Illuminate\Database\QueryException $ex){
                $this->response = $ex->getMessage();
            }
//        }

        $return_arr['alert'] = $this->response;

        echo json_encode($return_arr);
    }
}
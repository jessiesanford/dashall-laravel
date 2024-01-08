<?php

namespace App\Console\Commands;

use App\OrderAddress;
use App\OrderCost;
use App\StripeCharge;
use App\StripeCustomer;
use Auth;
use App\User;
use App\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe;

Stripe\Stripe::setApiKey(env("STRIPE_API_KEY"));

class StripeService
{
    public function getStripeCustomer($user)
    {
        $stripeID = StripeCustomer::where('user_id', $user->user_id)->first();
        if (!empty($stripeID)) {
            $customer = Stripe\Customer::retrieve($stripeID->stripe_id);
            return $customer->sources->data[0];
        }
        else {
            return [];
        }
    }



    public function verifyCustomer($user)
    {
        try
        {
            $stripeID = StripeCustomer::where('user_id', $user->user_id)->first()->stripe_id;
            $customer = \Stripe\Customer::retrieve($stripeID);
            $alerts[] = "Card Successfully Authorized!";
        }
        catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
        }
        catch (\Stripe\Error\RateLimit $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Too many requests made to the API too quickly
        }
        catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Invalid parameters were supplied to Stripe's API
        }
        catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Authentication with Stripe's API failed
        }
        catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Network communication with Stripe failed
        }
        catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Display a very generic error to the user
        }
        catch (Exception $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Something else happened, completely unrelated to Stripe
        }

        $return_arr['alert'] = $alerts[0];
        return $return_arr;
    }



    public function createStripeCustomer($user, Request $req)
    {
        try
        {
            $stripe_customer =  Stripe\Customer::create(array(
                "source" => $req->stripeToken,
                "description" => $user->first_name . ' ' . $user->last_name,
                "email" => $user->email
            ));

            $customer = new StripeCustomer();
            $customer->user_id = $user->user_id;
            $customer->stripe_id = $stripe_customer->id;
            $customer->save();

            $alerts[] = "Card Authorized Successfully.";
        }
        catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
        }
        catch (\Stripe\Error\RateLimit $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Too many requests made to the API too quickly
        }
        catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Invalid parameters were supplied to Stripe's API
        }
        catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Authentication with Stripe's API failed
        }
        catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Network communication with Stripe failed
        }
        catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Display a very generic error to the user
        }
        catch (Exception $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Something else happened, completely unrelated to Stripe
        }

        $return_arr['alert'] = $alerts[0];
        return $return_arr;
    }


    public function deleteStripeCustomer($user)
    {
        $customer = StripeCustomer::where('user_id', $user->user_id)->first();
        $customer->delete();
    }



    public function chargeStripeCustomer(User $user, Order $order, $amount)
    {
        $customer = $this->getStripeCustomer($user);

        try
        {

            $stripeCharge = \Stripe\Charge::create(array(
                    "amount" => bcmul($amount, 100), // amount in cents, again
                    "currency" => "cad",
                    "customer" => $customer->customer
                )
            );

            // insert a row into our db
            $charge = new StripeCharge();
            $charge->charge_id = $stripeCharge->id;
            $charge->order_id = $order->order_id;
            $charge->save();

            $alerts[] = "Payment Processed Successfully.";
        }
        catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
        }
        catch (\Stripe\Error\RateLimit $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Too many requests made to the API too quickly
        }
        catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Invalid parameters were supplied to Stripe's API
        }
        catch (\Stripe\Error\Authentication $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Authentication with Stripe's API failed
        }
        catch (\Stripe\Error\ApiConnection $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Network communication with Stripe failed
        }
        catch (\Stripe\Error\Base $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Display a very generic error to the user
        }
        catch (Exception $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $alerts[] = $err['message'];
            $return_arr['error'] = true;
            // Something else happened, completely unrelated to Stripe
        }

        $alerts[] = $amount;
        $return_arr['error'] = false;
        $return_arr['alert'] = $alerts[0];
        return $return_arr;
    }
}



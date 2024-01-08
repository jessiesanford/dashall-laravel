<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user/login', [
            'title' => 'Login',
        ]);
    }


    public function login(Guard $auth)
    {
        if (Auth::attempt(['email' => $_POST['email'], 'password' => $_POST['password']], true)) {

            $return_arr['alert'] = "Logging in...";
        }
        else {
            $return_arr['formCheck'] = 'error';
            $return_arr['alert'] = "The username and password are incorrect.";
        }

        echo json_encode($return_arr);
    }

    public function logout() {
        Auth::logout();
        $return_arr['alert'] = "Logging Out...";
        echo json_encode($return_arr);
    }


}

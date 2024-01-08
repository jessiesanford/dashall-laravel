<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    public $return_arr = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user/register', [
            'title' => 'Register',
        ]);
    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(Request $data)
    {
        $data['phone'] = str_replace("-", "", $data['phone']);

        $validator = Validator::make($data->all(), [
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|regex:/[0-9]{10}/|max:10|unique:users',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required|min:5',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255'
        ]);


        if ($validator->fails()) {
            $this->return_arr['errors'] = $validator->errors();
            return $this->return_arr;
        }
        else {
            $this->create($data->all());
            $this->return_arr['alert'] = "Successfully registered!";
            return $this->return_arr;
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'user_group' => 0,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'password' => bcrypt($data['password']),
            'email' => $data['email'],
            'phone' => $data['phone'],
            'reg_date' => Carbon::now(),
            'survey' => $data['survey']
        ]);
    }


    public function register(Request $req)
    {
        $response = $this->validator($req);
        echo json_encode($response);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\DriverShift;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use App\User;
use Carbon\Carbon;
use App\Order;

class UsersController extends Controller
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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/users', [
            'title' => 'Drivers',
            'users' => User::paginate(50),
            'userCount' => User::count()
        ]);
    }

    public function profile($id)
    {
        $user = User::find($id);
        $orders = Order::where('user_id', $id)->paginate(5);
        return view('admin/user_profile', [
            'title' => 'Profile of ' . $user->first_name . ' ' . $user->last_name,
            'user' => $user,
            'orders' => $orders
        ]);
    }

}

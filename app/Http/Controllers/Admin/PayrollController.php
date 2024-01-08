<?php

namespace App\Http\Controllers\Admin;

use App\DriverShift;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use App\Driver;
use App\DriverPayroll;
use Carbon\Carbon;
use App\Order;

class PayrollController extends Controller
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
        return view('admin/payroll', [
            'title' => 'Payroll',
            'payroll' => DriverPayroll::with('driver')->has('driver')->orderBy('time', 'desc')->paginate(50)
        ]);
    }

}

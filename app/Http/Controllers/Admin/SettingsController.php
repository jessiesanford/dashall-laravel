<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\DriverShift;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use App\Driver;
use Carbon\Carbon;

use Illuminate\Support\Facades\Input;

class SettingsController extends Controller
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
        return view('admin/settings', [
            'title' => 'Settings',
            'drivers' => Driver::with('user')->get(),
            'activeDriver' => Driver::with('user')->where('driver_id', $this->settings['active_driver'])->first(),
            'todaysShifts' => DriverShift::with('driver')
                ->where(DB::raw('DATE(start)'), '=', Carbon::now()->format('Y-m-d'))
                ->where('start', '>' , Carbon::now()->setTime(12, 0, 0)->format('Y-m-d h:i:s'))
                ->get()
        ]);
    }


    public function updateSettings(Request $req)
    {
        foreach($req->all() as $key => $value) {
            DB::update('UPDATE settings SET value = ? WHERE name = ?', [$value, $key]);
        }

        $alerts[] = 'Settings Updated.';
        $return_arr['alert'] = $alerts;
        echo json_encode($return_arr);
    }


    public function userSearch(Request $req)
    {
        $query = $req->search_query;
        $results = User::where('first_name', 'LIKE', '%' . $query . '%')->get();

        $keyword = Input::get('search_query', '');
        $results = User::SearchByKeyword($keyword)->get();

        $return_arr['results'] = $results;
        echo json_encode($return_arr);
    }

}

<?php

namespace App\Http\Controllers;

use App\Console\Commands\DriverService;
use Illuminate\Http\Request;
use Auth;
use Session;

class StaticController extends Controller
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
        $this->driverService = new DriverService();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('something', [
            'title' => 'something',
        ]);
    }

}

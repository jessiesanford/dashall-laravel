<?php

namespace App\Http\Controllers;

use App\AssignableShift;
use App\Console\Commands\DriverService;
use Illuminate\Http\Request;
use Auth;
use PhpParser\Node\Expr\Assign;
use Session;
use App\Driver;
use App\DriverShift;
use Carbon\Carbon;
use DateTime;

use Log;


class ScheduleController extends Controller
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
        $this->driverService = new DriverService();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('schedule', [
            'title' => 'Driver Schedule',
            'driver' => Driver::where('driver_id', Auth::user()->user_id)->first(),
            'calendar' => $this->getCalendar(),
            'shifts' => $this->getShifts(),
            'assignableShifts' => $this->getAssignableShifts(),
            'driverShifts' => DriverShift::with('driver')->where('user_id', Auth::user()->user_id)->orderBy('start')->get()
        ]);
    }

    // TODO: is this secure???
    public function adminView()
    {
        return view('admin.schedule', [
            'title' => 'Driver Schedule',
            'driver' => Driver::where('driver_id', Auth::user()->user_id)->first(),
            'calendar' => $this->getCalendar(),
            'shifts' => $this->getShifts(),
            'assignableShifts' => $this->getAssignableShifts(),
            'driverShifts' => DriverShift::all()
        ]);
    }

    public function configureView()
    {
        return view('admin.schedule_configure', [
            'title' => 'Configure Driver Schedule',
            'daysOfWeek' => $this->getDaysOfWeek(),
            'shifts' => AssignableShift::orderBy('start')->get(),
            'hours' => $this->getHours()
        ]);
    }

    public function getDaysOfWeek()
    {
        $week = array();
        $week[0] = 'Sunday';
        $week[1] = 'Monday';
        $week[2] = 'Tuesday';
        $week[3] = 'Wednesday';
        $week[4] = 'Thursday';
        $week[5] = 'Friday';
        $week[6] = 'Saturday';

        return $week;
    }

    public function getHours()
    {
        $hours = array();
        $time = Carbon::createFromTime('0', '0', '0');

        for ($i = 0; $i < 48; $i++) {
            $hours[]= $time->format('g:ia');
            $time = new Carbon($time->modify('+30 minutes'));
        }

        return $hours;
    }

    public function getCalendar()
    {
        $current_week = array();
        $next_week = array();

        // this is fucking wonky, you'd think a language that's been prominant on the web for 15 years wouldn't need such a discusting hack
        if (date('w', strtotime('Today')) == 0)
        {
            $current_week[0] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week')));
            $current_week[1] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +1 days')));
            $current_week[2] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +2 days')));
            $current_week[3] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +3 days')));
            $current_week[4] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +4 days')));
            $current_week[5] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +5 days')));
            $current_week[6] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +6 days')));

            $next_week[0] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week')));
            $next_week[1] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +1 days')));
            $next_week[2] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +2 days')));
            $next_week[3] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +3 days')));
            $next_week[4] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +4 days')));
            $next_week[5] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +5 days')));
            $next_week[6] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +6 days')));
        }
        else
        {
            $current_week[0] = new DateTime(date('Y-m-d H:i:s', strtotime('last Sunday this week')));
            $current_week[1] = new DateTime(date('Y-m-d H:i:s', strtotime('Monday this week')));
            $current_week[2] = new DateTime(date('Y-m-d H:i:s', strtotime('Tuesday this week')));
            $current_week[3] = new DateTime(date('Y-m-d H:i:s', strtotime('Wednesday this week')));
            $current_week[4] = new DateTime(date('Y-m-d H:i:s', strtotime('Thursday this week')));
            $current_week[5] = new DateTime(date('Y-m-d H:i:s', strtotime('Friday this week')));
            $current_week[6] = new DateTime(date('Y-m-d H:i:s', strtotime('Saturday this week')));

            $next_week[0] = new DateTime(date('Y-m-d H:i:s', strtotime('last Sunday +7 days')));
            $next_week[1] = new DateTime(date('Y-m-d H:i:s', strtotime('Monday this week +7 days')));
            $next_week[2] = new DateTime(date('Y-m-d H:i:s', strtotime('Tuesday this week +7 days')));
            $next_week[3] = new DateTime(date('Y-m-d H:i:s', strtotime('Wednesday this week +7 days')));
            $next_week[4] = new DateTime(date('Y-m-d H:i:s', strtotime('Thursday this week +7 days')));
            $next_week[5] = new DateTime(date('Y-m-d H:i:s', strtotime('Friday this week +7 days')));
            $next_week[6] = new DateTime(date('Y-m-d H:i:s', strtotime('Saturday this week +7 days')));
        }

        $calendar['weeks']['this_week'] = $current_week;
        $calendar['weeks']['next_week'] = $next_week;
        $calendar['today'] = new DateTime(date('Y-m-d H:i:s', strtotime(Carbon::now())));


        return $calendar;
    }


    public function getShifts()
    {
        $calendar = $this->getCalendar();

        $index = 0;
        foreach ($calendar['weeks'] as $week)
        {
            foreach ($week as $day)
            {
                if ($day->format('D') == 'Fri' || $day->format('D') == 'Sat') {
                    $shifts[$index][$day->format('D')] = array(
                        array(
                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(17, 00),
                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(21, 00),
                            'drivers' => $this->getShiftDrivers(new Carbon($day->setTime(17, 00)->format('Y-m-d H:i:s'))),
                            'available' => $this->getShiftStatus(new Carbon($day->setTime(17, 00)->format('Y-m-d H:i:s')))
                        ),
                        array(
                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(18, 00),
                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(21, 00),
                            'drivers' => $this->getShiftDrivers(new Carbon($day->setTime(18, 00)->format('Y-m-d H:i:s'))),
                            'available' => $this->getShiftStatus(new Carbon($day->setTime(18, 00)->format('Y-m-d H:i:s')))
                        ),
                        array(
                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(21, 00),
                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(01, 00)->modify('+1 day'),
                            'drivers' => $this->getShiftDrivers(new Carbon($day->setTime(21, 00)->format('Y-m-d H:i:s'))),
                            'available' => $this->getShiftStatus(new Carbon($day->setTime(21, 00)->format('Y-m-d H:i:s')))
                        )
                    );
                } else {
                    $shifts[$index][$day->format('D')] = array(
                        array(
                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(17, 00),
                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(21, 00),
                            'drivers' => $this->getShiftDrivers(new Carbon($day->setTime(17, 00)->format('Y-m-d H:i:s'))),
                            'available' => $this->getShiftStatus(new Carbon($day->setTime(17, 00)->format('Y-m-d H:i:s')))
                        ),
                        array(
                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(21, 00),
                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(01, 00)->modify('+1 day'),
                            'drivers' => $this->getShiftDrivers(new Carbon($day->setTime(21, 00)->format('Y-m-d H:i:s'))),
                            'available' => $this->getShiftStatus(new Carbon($day->setTime(21, 00)->format('Y-m-d H:i:s')))
                        )
                    );
                }
            }
            $index++;
        }


        return $shifts;
    }


    public function getAssignableShifts()
    {
        $calendar = $this->getCalendar();
        $assignableShifts = AssignableShift::all();
        $index = 0;
        $shifts = array();

        // returns 2 arrays (2 week cycle) with 7 days each, with corresponding shifts.
        foreach ($calendar['weeks'] as $week)
        {
            foreach ($week as $day)
            {
                foreach($assignableShifts as $shiftTime) {
                    if (substr($shiftTime['day'], 0, 3) == $day->format('D')) {
                        $startHour = date('H', strtotime($shiftTime['start']));
                        $startMinute = date('i', strtotime($shiftTime['start']));
                        $endHour = date('H', strtotime($shiftTime['end']));
                        $endMinute = date('i', strtotime($shiftTime['end']));

                        $shifts[$index][] = array(
                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime($startHour, $startMinute),
                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime($endHour, $endMinute),
                            'drivers' => $this->getShiftDrivers(Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime($startHour, $startMinute)),
                            'available' => $this->getShiftStatus(Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime($startHour, $startMinute)),
                            'driverShift' => DriverShift::with('driver')->where('start', '2017-09-03 17:00:00')->first()
                        );

//                        error_log(Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime($startHour, $startMinute));
//                        error_log(Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime($startHour, $startMinute) == '2017-09-03 17:00:00');

                    }
                }
//                if ($day->format('D') == 'Fri' || $day->format('D') == 'Sat') {
//                    $shifts[$index][$day->format('D')] = array(
//                        array(
//                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(17, 00),
//                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(21, 00),
//                            'drivers' => $this->getShiftDrivers(new Carbon($day->setTime(17, 00)->format('Y-m-d H:i:s'))),
//                            'available' => $this->getShiftStatus(new Carbon($day->setTime(17, 00)->format('Y-m-d H:i:s')))
//                        ),
//                        array(
//                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(18, 00),
//                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(21, 00),
//                            'drivers' => $this->getShiftDrivers(new Carbon($day->setTime(18, 00)->format('Y-m-d H:i:s'))),
//                            'available' => $this->getShiftStatus(new Carbon($day->setTime(18, 00)->format('Y-m-d H:i:s')))
//                        ),
//                        array(
//                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(21, 00),
//                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(01, 00)->modify('+1 day'),
//                            'drivers' => $this->getShiftDrivers(new Carbon($day->setTime(21, 00)->format('Y-m-d H:i:s'))),
//                            'available' => $this->getShiftStatus(new Carbon($day->setTime(21, 00)->format('Y-m-d H:i:s')))
//                        )
//                    );
//                } else {
//                    $shifts[$index][$day->format('D')] = array(
//                        array(
//                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(17, 00),
//                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(21, 00),
//                            'drivers' => $this->getShiftDrivers(new Carbon($day->setTime(17, 00)->format('Y-m-d H:i:s'))),
//                            'available' => $this->getShiftStatus(new Carbon($day->setTime(17, 00)->format('Y-m-d H:i:s')))
//                        ),
//                        array(
//                            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(21, 00),
//                            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $day->format('Y-m-d H:i:s'))->setTime(01, 00)->modify('+1 day'),
//                            'drivers' => $this->getShiftDrivers(new Carbon($day->setTime(21, 00)->format('Y-m-d H:i:s'))),
//                            'available' => $this->getShiftStatus(new Carbon($day->setTime(21, 00)->format('Y-m-d H:i:s')))
//                        )
//                    );
//                }
            }
            $index++;
        }

        return $shifts;
    }



    public function getShiftDrivers($start)
    {
        return DriverShift::with('driver')->where('start', $start)->get();
    }

    public function getShiftStatus($start)
    {
        $shift = DriverShift::with('driver')->where('start', $start)->first();


//        if ($shift['start'] == $start) {
//            error_log(($shift['start'] == $start) . " supplied start: " . $start . ", actual start: " . $shift['start']);
////            error_log(($shift['start'] == $start));
//        }

        if ($shift == null) {
            return true;
        }
        else {
            error_log($shift);
            error_log('made IT AT ONE POINT');
            return false;
        }
    }


    public function takeShift(Request $req)
    {
        $shift = DriverShift::where('start', $req->shift_start)->first();

        if($shift != null)
        {
            $alerts[] = 'This shift has already been assigned.';
        }
        else
        {
            $this->driverService->takeShift(Auth::user()->user_id, $req->shift_start, $req->shift_end);
            $alerts[] = 'Shift Assigned to you!';
        }

        $return_arr['alert'] = $alerts[0];
        $return_arr['debug'] = $shift;
        echo json_encode($return_arr);
    }


    public function reqUnshift(Request $req)
    {
        $shift = DriverShift::where('shift_id', $req->shift_id)->first();
        $shift->setReqUnshift(1);

        $return_arr['alert'] = "You have requested a shift change.";
        echo json_encode($return_arr);
    }

    public function removeShift(Request $req)
    {
        $shift = DriverShift::where('shift_id', $req->shift_id);
        $shift->delete();

        $return_arr['alert'] = "This shift has been deleted.";
        echo json_encode($return_arr);
    }

    public function createShift(Request $req)
    {
        try
        {
            $shift = new AssignableShift();
            $shift->day = $req->shift_day;
            $shift->start = $req->shift_start;
            $shift->end = $req->shift_end;
            $shift->save();
        }
        catch(\Illuminate\Database\QueryException $ex){
            $error = $ex->getMessage();
        }

        if (isset($error)) {
            $return_arr['alert'] = 'This shift already exists.' . $error;
        }
        else {
            $return_arr['alert'] = "A shift has been created.";
        }

        echo json_encode($return_arr);
    }


    public function deleteShift(Request $req)
    {
        try
        {
            AssignableShift::where(
                'day', $req->shift_day
            )->where('start', $req->shift_start)->where('end', $req->shift_end)->delete();

        }
        catch(\Illuminate\Database\QueryException $ex){
            $error = $ex->getMessage();
        }

        if (isset($error)) {
            $return_arr['alert'] = 'Cannot delete shift.' . $error;

        }
        else {
            $return_arr['alert'] = "Shift Removed.";
        }

        echo json_encode($return_arr);
    }

}

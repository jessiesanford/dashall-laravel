<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\DB;
use App\Http\Misc\StoreHours;
use View;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $storeHours;

    public function __construct()
    {
        $this->settings = $this->getSettings();
        $this->storeHours = $this->getStoreHours();
        $this->online = $this->getOnlineStatus();
        View::share('time', Carbon::now()->format('Y-m-d h:i:s'));
        View::share('settings', $this->settings);
        View::share('online', $this->online);
        View::share('store_hours', $this->storeHours);
    }

    public function getStoreHours()
    {
        $hours = array(
            'sun' => array('12:00-00:00'),
            'mon' => array('17:00-00:00'),
            'tue' => array('17:00-00:00'),
            'wed' => array('17:00-00:00'),
            'thu' => array('17:00-00:00'),
            'fri' => array('17:00-03:00'),
            'sat' => array('16:00-03:00')
        );

        $exceptions = array(
            '2/24'  => array('11:00-18:00'),
            '10/18' => array('11:00-16:00', '18:00-20:30')
        );

        $template = array(
            'open'           => "Open from {%hours%}.",
            'closed'         => "Sorry, we're closed. Today's hours are {%hours%}.",
            'closed_all_day' => "Sorry, we're closed today.",
            'separator'      => " - ",
            'join'           => " and ",
            'format'         => "g:ia", // options listed here: http://php.net/manual/en/function.date.php
            'hours'          => "{%open%}{%separator%}{%closed%}"
        );
        return new StoreHours($hours, $exceptions, $template);
    }


    public function getSettings()
    {
        $db_settings = DB::table('settings')->get();

        $settings = array();

        foreach ($db_settings as $setting) {
            $settings[$setting->name] = $setting->value;
        }

        return $settings;
    }


    public function getOnlineStatus()
    {
        if ($this->settings['force_operation'] == 1) {
            $settings['open_notice'] = "OPENED FOR DEV.";
            $takingOrders = true;
        }
        else if ($this->settings['taking_orders'] == 0) {
            $takingOrders = false;
        }
        else if ($this->settings['taking_orders'] == 1)
        {
            if ($this->storeHours->is_open())
            {
                $takingOrders = true;
            }
            else
            {
                $takingOrders = false;
            }
        }
        else {
            $takingOrders = false;
        }

        return $takingOrders;
    }

}

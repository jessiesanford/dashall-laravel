<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'status_id';
    protected $table = 'order_status';

    public function order()
    {
        return $this->belongsToMany('App\Order', 'status_id', 'status_id');
    }

    public function getOrderStatusList()
    {
        return $this->all();
    }

    public function getRank()
    {
        return $this->attributes['rank'];
    }

    public function getProcessingStatusList()
    {
//        return $this->all()->where('rank', '>', -1);
        return $this->all();
    }

    public static function getStatusList()
    {
//        return $this->all()->where('rank', '>', -1);
        return 0;
    }

    public function canPromoteOrder()
    {

    }

    public static function getAllStatusIDs()
    {
        return self::all()->pluck('status_id');
    }

    public static function pluckStatusIDs($statusList) {
        return self::all()->whereIn('status_id', $statusList)->pluck('status_id');
    }
}

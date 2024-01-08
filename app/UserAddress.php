<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'user_id';
    protected $table = 'user_addresses';

    public function order()
    {
        return $this->belongsTo('App\User' ,'user_id');
    }
}

<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $primaryKey = 'user_id';
    protected $table = 'users';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'user_group', 'email', 'phone', 'password', 'survey', 'reg_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function address()
    {
        return $this->hasOne('App\UserAddress', 'user_id', 'user_id');
    }


    public function order()
    {
        return $this->hasMany('App\Order');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver', 'user_id', 'user_id');
    }

    public function stripeCustomer() {
        return $this->hasOne('App\StripeCustomer', 'user_id', 'user_id');
    }



    // for admin cp
    public function scopeSearchByKeyword($query, $keyword)
    {
        if ($keyword!='') {
            $query->where(function ($query) use ($keyword) {
                $query->where("first_name", "LIKE","%$keyword%")
                    ->orWhere('last_name', 'LIKE', "%$keyword%")
                    ->orWhere("email", "LIKE", "%$keyword%")
                    ->orWhere("phone", "LIKE", "%$keyword%");
            });
        }
        return $query;
    }

}

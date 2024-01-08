<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'promo_code';
    protected $table = 'promos';

    public function order() {
        $this->belongsToMany('App\Order', 'promo');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $guarded = [];

    public function customer(){
        return $this->belongsTo('App\Customer','customer_id','id');
    }

    public function payment(){
        return $this->hasOne('App\Distribution_payment','distribution_id','id');
    }

    public function distribution_item(){
        return $this->hasOne('App\Distribution_item','distribution_id','id');
    }

    public function distribution_items(){
        return $this->hasMany('App\Distribution_item', 'distribution_id', 'id');
    }
}

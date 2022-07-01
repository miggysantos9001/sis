<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $guarded = [];

    public function customer(){
        return $this->belongsTo('App\Customer','customer_id','id');
    }
}

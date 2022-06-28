<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    protected $guarded = [];

    public function branch(){
        return $this->belongsTo('App\Branch','branch_id','id');
    }

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}

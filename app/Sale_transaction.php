<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale_transaction extends Model
{
    protected $guarded = [];

    public function sale(){
        return $this->belongsTo('App\Sale','sale_id','id');
    }

    public function cashier(){
        return $this->belongsTo('App\Cashier','cashier_id','id');
    }

    public function branch(){
        return $this->belongsTo('App\Branch','branch_id','id');
    }

}

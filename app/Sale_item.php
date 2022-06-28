<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale_item extends Model
{
    protected $guarded = [];

    public function sale(){
        return $this->belongsTo('App\Sale','sale_id','id');
    }

    public function product(){
        return $this->belongsTo('App\Product','product_id','id');
    }

    public function branch(){
        return $this->belongsTo('App\Branch','branch_id','id');
    }
}

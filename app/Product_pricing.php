<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_pricing extends Model
{
    protected $guarded = [];

    public function product(){
        return $this->belongsTo('App\Product','product_id','id');
    }

    public function branch(){
        return $this->belongsTo('App\Branch','branch_id','id');
    }
}

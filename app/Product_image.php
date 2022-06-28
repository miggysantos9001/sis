<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_image extends Model
{
    protected $guarded = [];

    public function product(){
        return $this->belongsTo('App\Product','product_id','id');
    }
}

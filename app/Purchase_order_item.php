<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase_order_item extends Model
{
    protected $guarded = [];

    public function po(){
        return $this->belongsTo('App\Purchase_order','purchase_order_id','id');
    }

    public function product(){
        return $this->belongsTo('App\Product','product_id','id');
    }

    public function uom(){
        return $this->belongsTo('App\Unit','uom_id','id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distribution_item extends Model
{
    protected $guarded = [];

    public function po_item(){
        return $this->belongsTo('App\Purchase_order_item','purchase_order_item_id','id');
    }

    public function distribution(){
        return $this->belongsTo('App\Distribution','distribution_id','id');
    }
}

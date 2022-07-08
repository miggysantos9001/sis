<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase_order_item_total extends Model
{
    protected $guarded = [];

    public function po_item(){
        return $this->belongsTo('App\Purchase_order_item','purchase_order_item_id','id');
    }

}

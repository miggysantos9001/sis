<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receive_po_item extends Model
{
    protected $guarded = [];

    public function rr(){
        return $this->belongsTo('App\Receive_purchase_order','rr_id','id');
    }

    public function po_item(){
        return $this->belongsTo('App\Purchase_order_item','purchase_order_item_id','id');
    }
}

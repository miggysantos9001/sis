<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receive_purchase_order extends Model
{
    protected $guarded = [];

    public function po(){
        return $this->belongsTo('App\Purchase_order','purchase_order_id','id');
    }
}

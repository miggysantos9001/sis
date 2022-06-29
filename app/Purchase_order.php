<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase_order extends Model
{
    protected $guarded = [];

    public function supplier(){
        return $this->belongsTo('App\Supplier','supplier_id','id');
    }

    public function branch(){
        return $this->belongsTo('App\Branch','branch_id','id');
    }

    public function po_items(){
        return $this->hasMany('App\Purchase_order_item', 'purchase_order_id', 'id');
    }
}

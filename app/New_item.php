<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class New_item extends Model
{
    protected $guarded = [];

    public function supplier(){
        return $this->belongsTo('App\Supplier','supplier_id','id');
    }

    public function branch(){
        return $this->belongsTo('App\Branch','branch_id','id');
    }

    public function product(){
        return $this->belongsTo('App\Product','product_id','id');
    }
}

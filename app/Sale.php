<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];

    public function cashier(){
        return $this->belongsTo('App\Cashier','cashier_id','id');
    }

    public function saleitems(){
        return $this->hasMany('App\Sale_item', 'sale_id', 'id');
    }

}

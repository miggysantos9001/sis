<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function getProductNameAttribute(){
        return $this->description;
    }

    public function category(){
        return $this->belongsTo('App\Category','category_id','id');
    }

    public function pricings(){
        return $this->hasMany('App\Product_pricing', 'product_id', 'id')->orderBy('id','DESC');
    }

    public function pricing(){
        return $this->hasOne('App\Product_pricing', 'product_id', 'id')->orderBy('id','DESC');
    }

    public function images(){
        return $this->hasMany('App\Product_image', 'product_id', 'id');
    }

}

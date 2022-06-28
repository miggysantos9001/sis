<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    public function getSupplierNameAttribute(){
        return $this->name;
    }
}

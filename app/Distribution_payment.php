<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distribution_payment extends Model
{
    protected $guarded = [];

    public function distribution(){
        return $this->belongsTo('App\Distribution','distribution_id','id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];

    public function getBranchNameAttribute(){
        return $this->name;
    }
}

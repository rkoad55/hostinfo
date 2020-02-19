<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Batch extends Model
{
    //
    protected $guarded = ['id'];

    public function domain() {
        return $this->belongsToMany('App\Domain');
    }

     
}

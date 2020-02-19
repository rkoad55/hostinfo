<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    //
 protected $guarded = ['id'];

    public function domain() {
        return $this->belongsTo('App\Domain');
    }   
}

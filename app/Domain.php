<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    //
    protected $guarded = ['id'];

    public function batch() {
        return $this->belongsToMany('App\Batch');
    }

    public function history() {
        return $this->hasMany('App\History');
    }
}

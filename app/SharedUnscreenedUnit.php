<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SharedUnscreenedUnit extends Model
{
    protected $table = "shared_unscreened_units";

    function test(){
        return $this->belongsTo('App\TestResult','donation_id','donation_id');
    }

    function type(){
        return $this->belongsTo('App\TypingResult','donation_id','donation_id');
    }

    function discards(){
        return $this->hasMany('App\Discard','donation_id','donation_id');
    }

    function labels(){
        return $this->hasMany('App\Label','donation_id','donation_id');
    }

    function donation(){
        return $this->belongsTo('App\Donation','donation_id','donation_id');
    }
}

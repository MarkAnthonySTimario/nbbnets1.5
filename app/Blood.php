<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blood extends Model
{
    protected $table = 'component';
    public $timestamps = false;
    public $incrementing = false;
    public $primaryKey = 'donation_id';

    function test(){
        return $this->belongsTo('App\TestResult','donation_id','donation_id');
    }
    
    function discard(){
        return $this->belongsTo('App\Discard','donation_id','donation_id')->whereComponentCd($this->component_cd);
    }
    
    function donation(){
        return $this->belongsTo('App\Donation','donation_id','donation_id');
    }

    function aliqoute_donation(){
        return $this->belongsTo('App\Donation','source_donation_id','donation_id');
    }
    
    function donation_min(){
        return $this->belongsTo('App\Donation','donation_id','donation_id')->select('donation_id','sched_id','created_dt');
    }

    function component(){
        return $this->belongsTo('App\Component','component_cd','component_cd');
    }

    function component_min(){
        return $this->belongsTo('App\Component','component_cd','component_cd')->select('component_cd','comp_name');
    }
}

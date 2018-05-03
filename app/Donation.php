<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $table = 'donation';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'seqno';

    function donor(){
        return $this->belongsTo('App\Donor','donor_sn','seqno')->exclude('donor_photo');
    }

    function processing(){
        return $this->belongsTo('App\Processing','donation_id','donation_id');
    }

    function facility(){
        return $this->belongsTo('App\Facility','facility_cd','facility_cd');
    }

    function test(){
        return $this->belongsTo('App\TestResult','donation_id','donation_id');
    }

    function type(){
        return $this->belongsTo('App\TypingResult','donation_id','donation_id');
    }

    function mbd(){
        return $this->belongsTo('App\MBD','sched_id','sched_id');
    }

    function units(){
        return $this->hasMany('App\Blood','donation_id','donation_id');
    }

    function discards(){
        return $this->hasMany('App\Discard','donation_id','donation_id');
    }
}

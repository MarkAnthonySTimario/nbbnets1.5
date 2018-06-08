<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransfusionRequest extends Model
{
    protected $table = 'bts_blood_request';
    public $incrementing = false;
    public $timestamps = false;

    function details(){
        return $this->hasMany('App\TransfusionRequestDetail','request_id','request_id');
    }

    function patient(){
        return $this->belongsTo('App\TransfusionPatient','patient_id','patient_id');
    }
    
    function physician(){
        return $this->belongsTo('App\TransfusionPhysician','physician_id','physician_id');
    }
}

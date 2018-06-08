<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransfusionRequestDetail extends Model
{
    protected $table = 'bts_blood_request_dtls';
    public $incrementing = false;
    public $timestamps = false;

    function request(){
        $this->belongsTo('App\TransfusionRequest','request_no','request_no');
    }
}

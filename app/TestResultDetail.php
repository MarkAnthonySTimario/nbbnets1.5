<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestResultDetail extends Model
{
    protected $table = 'bloodtest_dtls';
    public $incrementing = false;
    public $timestamps = false;

    function header(){
        return $this->belongsTo('App\TestResult','bloodtest_no','bloodtest_no');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = 'r_donor_agency';
    public $timestamps = false;
    protected $primaryKey = 'agency_cd';

    function region(){
        return $this->belongsTo('App\Region','adg_region','regcode')->select('regcode','regname');
    }

    function province(){
        return $this->belongsTo('App\Province','adg_prov','provcode')->select('provcode','provname');
    }

    function city(){
        return $this->belongsTo('App\City','adg_city','citycode')->select('citycode','cityname');
    }

    function barangay(){
        return $this->belongsTo('App\Barangay','adg_bgy','bgycode')->select('bgycode','bgyname');
    }
}

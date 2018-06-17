<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'r_facility';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'facility_cd';

    function region(){
        return $this->belongsTo('App\Region','address_region','regcode')->select('regcode','regname');
    }

    function province(){
        return $this->belongsTo('App\Province','address_prov','provcode')->select('provcode','provname');
    }

    function city(){
        return $this->belongsTo('App\City','address_citymun','citycode')->select('citycode','cityname');
    }

    function barangay(){
        return $this->belongsTo('App\Barangay','address_bgy','bgycode')->select('bgycode','bgyname');
    }

    function users(){
        return $this->hasMany('App\User','facility_cd','facility_cd');
    }
}

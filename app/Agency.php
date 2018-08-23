<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = 'r_donor_agency';
    public $timestamps = false;
    protected $primaryKey = 'agency_cd';
    protected $casts = [
        'agency_cd' => 'string'
    ];

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

    static function generateAgencyCd($facility_cd,$i = 1,$max = null){
        if(!$max){
            $firstRecord = Agency::select('agency_cd')->whereFacilityCd($facility_cd)->orderBy('agency_cd','desc')->first();
            
            if($firstRecord){
                $max = $firstRecord->agency_cd;
            }else if(!$max){
                return $facility_cd.str_pad('1',5,'0',STR_PAD_LEFT);
            }
        }

        $new = $max+$i;
        $isExists = Agency::whereAgencyCd($new)->first();
        if($isExists){
            $i++;
            return self::generateAgencyCd($facility_cd,$i,$max);
        }

        return $new;
    }

    function donors(){
        return $this->hasMany('App\Don','agency_cd','agency_cd')->where('created_dt','like','2017-%');
    }

    // function fdonors(){
    //     return $this->hasMany('App\Don','agency_cd','agency_cd')->whereDonations(1);
    // }
}

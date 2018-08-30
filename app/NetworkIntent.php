<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NetworkIntent extends Model
{
    protected $table = 'networking_intent';
    
    static function generateNo($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = self::selectRaw('request_no')
                    ->whereTo($facility_cd)
                    ->where('request_no','like',$facility_cd.date('y').'%')
                    ->orderBy('request_no','desc')
                    ->first();
            if($max){
                $max = $max->request_no;
            }else if(!$max){
                return $facility_cd.date('y').str_pad('1',3,'0',STR_PAD_LEFT);
            }
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('y').str_pad($num,3,'0',STR_PAD_LEFT);
        $isExists = self::whereRequestNo($new)->first();
        if($isExists){
            $i++;
            return self::generateNo($facility_cd,$i,$max);
        }

        return $new;
    }

    function facilityFrom(){
        return $this->belongsTo('App\Facility','from','facility_cd')->select('facility_cd','facility_name');
    }

    function details(){
        return $this->hasMany('App\NetworkIntentDetail','intent_id','id');
    }
}

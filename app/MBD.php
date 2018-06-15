<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MBD extends Model
{
    protected $table = 'donation_schedules';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'sched_id';

    function donations(){
        return $this->hasMany('App\Donation','sched_id','sched_id');
    }

    function generateSchedID($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = MBD::select('sched_id')->whereFacilityCd($facility_cd)->orderBy('sched_id','desc')->first();
            if($max){
                $max = $max->sched_id;
            }else if(!$max){
                return $facility_cd.date('Y').str_pad('1',5,'0',STR_PAD_LEFT);
            }
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);
        $isExists = MBD::whereSchedId($new)->first();
        if($isExists){
            $i++;
            return self::generateSchedID($facility_cd,$i,$max);
        }

        return $new;
    }
}

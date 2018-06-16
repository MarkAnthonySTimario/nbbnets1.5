<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Processing extends Model
{
    protected $table = 'bloodproc';
    public $timestamps = false;
    public $primaryKey = 'bloodproc_no';
    public $incrementing = false;

    static function generateNo($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = self::selectRaw('max(bloodproc_no)')
                    ->whereFacilityCd($facility_cd)
                    ->where('bloodproc_no','like',$facility_cd.'%')
                    ->first();
            if($max){
                $max = $max->bloodproc_no;
            }else if(!$max){
                return $facility_cd.date('Y').str_pad('1',7,'0',STR_PAD_LEFT);
            }
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);
        $isExists = self::whereBloodprocNo($new)->first();
        if($isExists){
            $i++;
            return self::generateNo($facility_cd,$i,$max);
        }

        return $new;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypingResult extends Model
{
    protected $table = "blood_typing";
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'bloodtyping_no';

    static function generateNo($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = TypingResult::select('bloodtyping_no')
                    ->whereFacilityCd($facility_cd)
                    ->where('bloodtyping_no','like',$facility_cd.'%')
                    ->orderBy('bloodtyping_no','desc')->first();
            if($max){
                $max = $max->bloodtyping_no;
            }else if(!$max){
                return $facility_cd.date('Y').str_pad('1',7,'0',STR_PAD_LEFT);
            }
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);
        $isExists = TypingResult::whereBloodtypingNo($new)->first();
        if($isExists){
            $i++;
            return self::generateNo($facility_cd,$i,$max);
        }

        return $new;
    }
}

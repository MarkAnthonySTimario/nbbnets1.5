<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BSI_p8;
use App\TransfusionRequest;
use App\TransfusionPatient;

class BSI_p9 extends BSI_p8{

    function item_6_4(){
        return TransfusionRequest::whereFacilityCd($this->facility_cd)
                ->where('created_dt','like',$this->year.'-'.$this->month.'-%')
                ->whereStatus('I')
                ->get()->count();
    }
            
    function sub_item_6_5_($compute_format){
        $male_patients = TransfusionRequest::whereFacilityCd($this->facility_cd)
                ->where('created_dt','like',$this->year.'-'.$this->month.'-%')
                ->whereStatus('I')
                ->whereHas('patient',function($query) use ($compute_format){
                    $query->whereGender('M');
                    $query->whereRaw($compute_format);
                })
                ->get();

        $female_patients = TransfusionRequest::whereFacilityCd($this->facility_cd)
                ->where('created_dt','like',$this->year.'-'.$this->month.'-%')
                ->whereStatus('I')
                ->whereHas('patient',function($query) use ($compute_format){
                    $query->whereGender('F');
                    $query->whereRaw($compute_format);
                })
                ->get();

        return ['male' => $male_patients->count(), 'female' => $female_patients->count()];
    }

    function item_6_5_1(){
        $age = "(DATE_FORMAT(FROM_DAYS(DATEDIFF(bts_blood_request.created_dt,STR_TO_DATE(bdate,'%Y-%m-%d'))), '%Y')+0)";
        $compute_age = "$age < 5";
        return $this->sub_item_6_5_($compute_age);
    }

    function item_6_5_2(){
        $age = "(DATE_FORMAT(FROM_DAYS(DATEDIFF(bts_blood_request.created_dt,STR_TO_DATE(bdate,'%Y-%m-%d'))), '%Y')+0)";
        $compute_age = "$age BETWEEN 4 AND 15";
        return $this->sub_item_6_5_($compute_age);
    }

    function item_6_5_3(){
        $age = "(DATE_FORMAT(FROM_DAYS(DATEDIFF(bts_blood_request.created_dt,STR_TO_DATE(bdate,'%Y-%m-%d'))), '%Y')+0)";
        $compute_age = "$age BETWEEN 14 AND 45";
        return $this->sub_item_6_5_($compute_age);
    }

    function item_6_5_4(){
        $age = "(DATE_FORMAT(FROM_DAYS(DATEDIFF(bts_blood_request.created_dt,STR_TO_DATE(bdate,'%Y-%m-%d'))), '%Y')+0)";
        $compute_age = "$age BETWEEN 44 AND 60";
        return $this->sub_item_6_5_($compute_age);
    }

    function item_6_5_5(){
        $age = "(DATE_FORMAT(FROM_DAYS(DATEDIFF(bts_blood_request.created_dt,STR_TO_DATE(bdate,'%Y-%m-%d'))), '%Y')+0)";
        $compute_age = "$age >= 60";
        return $this->sub_item_6_5_($compute_age);
    }
}
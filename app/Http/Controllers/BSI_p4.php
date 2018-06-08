<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BSI_p3;
use App\Donation;

class BSI_p4 extends BSI_p3{

    function sub_item_2_8_($compute_format){


        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereNotNull('donor_sn')
                ->whereCollectionMethod('WB')
                ->whereHas('donor',function($query) use ($compute_format){
                    $query->whereRaw($compute_format);
                })
                ->get();
        
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->whereCollectionMethod('WB')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereNotNull('donor_sn')
                ->whereHas('donor',function($query) use ($compute_format){
                    $query->whereRaw($compute_format);
                })
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_8_1(){
        $age = "(DATE_FORMAT(FROM_DAYS(DATEDIFF(donation.created_dt,STR_TO_DATE(bdate,'%Y-%m-%d'))), '%Y')+0)";
        $compute_age = "$age < 18";
        return $this->sub_item_2_8_($compute_age);
    }

    function item_2_8_2(){
        $age = "(DATE_FORMAT(FROM_DAYS(DATEDIFF(donation.created_dt,STR_TO_DATE(bdate,'%Y-%m-%d'))), '%Y')+0)";
        $compute_age = "$age BETWEEN 17 AND 25";
        return $this->sub_item_2_8_($compute_age);
    }

    function item_2_8_3(){
        $age = "(DATE_FORMAT(FROM_DAYS(DATEDIFF(donation.created_dt,STR_TO_DATE(bdate,'%Y-%m-%d'))), '%Y')+0)";
        $compute_age = "$age BETWEEN 24 AND 45";
        return $this->sub_item_2_8_($compute_age);
    }

    function item_2_8_4(){
        $age = "(DATE_FORMAT(FROM_DAYS(DATEDIFF(donation.created_dt,STR_TO_DATE(bdate,'%Y-%m-%d'))), '%Y')+0)";
        $compute_age = "$age BETWEEN 44 AND 65";
        return $this->sub_item_2_8_($compute_age);
    }

    function item_2_8_5(){
        $age = "(DATE_FORMAT(FROM_DAYS(DATEDIFF(donation.created_dt,STR_TO_DATE(bdate,'%Y-%m-%d'))), '%Y')+0)";
        $compute_age = "$age >= 65";
        return $this->sub_item_2_8_($compute_age);
    }
    
    function item_2_8_6(){
        $mbd = Donation::with('donor')->whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereNotNull('donor_sn')
                ->whereCollectionMethod('WB')
                ->get();
        
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereNotNull('donor_sn')
                ->whereCollectionMethod('WB')
                ->get();

        return $mbd->count()+$walkin->count();
    }
}
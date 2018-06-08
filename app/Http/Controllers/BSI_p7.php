<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BSI_p6;
use App\Donation;

class BSI_p7 extends BSI_p6{

    function item_5_2(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereHas('processing',function($query){
                    $query->whereNotNull('bloodproc_no');
                })
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereHas('processing',function($query){
                    $query->whereNotNull('bloodproc_no');
                })
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function sub_item_5_3($component_cd){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereHas('units',function($query) use ($component_cd){
                    $query->whereComponentCd($component_cd);
                })
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereHas('units',function($query) use ($component_cd){
                    $query->whereComponentCd($component_cd);
                })
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_5_3_1(){
        return $this->sub_item_5_3(20);
    }

    function item_5_3_2(){
        return $this->sub_item_5_3(30);
    }

    // Cant decide what to do
    function item_5_3_3(){
        return 0;
    }

    function item_5_3_4(){
        return $this->sub_item_5_3(40);
    }

    function item_5_3_5(){
        return $this->sub_item_5_3(50);
    }

    function sub_item_5_4($component_cd){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('P')
                ->whereHas('units',function($query) use ($component_cd) {
                    $query->whereComponentCd($component_cd);
                })
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('P')
                ->whereHas('units',function($query) use ($component_cd) {
                    $query->whereComponentCd($component_cd);
                })
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_5_4_1(){
        return $this->sub_item_5_4(20);
    }

    function item_5_4_2(){
        return $this->sub_item_5_4(30);
    }

    // Cant decide what to do
    function item_5_4_3(){
        return 0;
    }

    function item_5_4_4(){
        return $this->item_5_4_1() + $this->item_5_4_2() + $this->item_5_4_3();
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BSI_p2;
use App\Donation;

class BSI_p3 extends BSI_p2{

    function item_2_6_1(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereDonationType('V')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereDonationType('V')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_6_2(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereDonationType('R')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereDonationType('R')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_6_3(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereDonationType('P')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereDonationType('P')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_6_4(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_7_1(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereHas('donor',function($query){
                    $query->whereGender('M');
                })
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereHas('donor',function($query){
                    $query->whereGender('M');
                })
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_7_2(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereHas('donor',function($query){
                    $query->whereGender('F');
                })
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereHas('donor',function($query){
                    $query->whereGender('F');
                })
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_7_3() {
        return $this->item_2_7_1()+$this->item_2_7_2();
    }

    
}
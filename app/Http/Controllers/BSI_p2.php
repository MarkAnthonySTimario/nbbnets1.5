<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BSI_p1;
use App\Donation;

class BSI_p2 extends BSI_p1{

    function item_2_5_1(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereMhPeDeferral('2.5.1')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereMhPeDeferral('2.5.1')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_5_2(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereMhPeDeferral('2.5.2')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereMhPeDeferral('2.5.2')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_5_3(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereMhPeDeferral('2.5.3')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereMhPeDeferral('2.5.3')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_5_4(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereMhPeDeferral('2.5.4')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereMhPeDeferral('2.5.4')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_5_5(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereIn('mh_pe_deferral',['2.5.1','2.5.2','2.5.3','2.5.4'])
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereIn('mh_pe_deferral',['2.5.1','2.5.2','2.5.3','2.5.4'])
                ->get();

        return $mbd->count()+$walkin->count();
    }

}
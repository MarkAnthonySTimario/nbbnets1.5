<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BSI_p4;
use App\Donation;

class BSI_p5 extends BSI_p4{

    function item_2_9(){
        $mbd = Donation::with('donor.donations')->whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereNotNull('donor_sn')
                ->whereDonationType('V')
                ->get();
        
        $walkin = Donation::with('donor.donations')->whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereNotNull('donor_sn')
                ->whereDonationType('V')
                ->get();

        $donations = [];
        foreach($mbd as $d){
            if($d->donor){
                if($d->donor->donations->count() == 1){
                    $donations[] = $d;
                }
            }
        }

        foreach($walkin as $d){
            if($d->donor){
                if($d->donor->donations->count() == 1){
                    $donations[] = $d;
                }
            }
        }

        $donations = collect($donations);

        return $donations->count();
    }

    function item_2_10(){
        $mbd = Donation::with('donor')->whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereNotNull('donor_sn')
                ->whereDonationType('V')
                ->whereCollectionStat('COL')
                ->get();
        
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereNotNull('donor_sn')
                ->whereDonationType('V')
                ->whereCollectionStat('COL')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_11_1(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('P')
                ->whereDonationType('V')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('P')
                ->whereDonationType('V')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_11_2(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('P')
                ->whereDonationType('R')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('P')
                ->whereDonationType('R')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_11_3(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('P')
                ->whereDonationType('P')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('P')
                ->whereDonationType('P')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_11_4(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('P')
                ->whereNotIn('donation_type',['A','R','P'])
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('P')
                ->whereNotIn('donation_type',['A','R','P'])
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_11_5(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('P')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('P')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    // incomplete
    function item_2_11_6(){
        return 0;
    }
}
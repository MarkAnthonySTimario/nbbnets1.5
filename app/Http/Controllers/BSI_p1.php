<?php

namespace App\Http\Controllers;

use App\Donation;
use App\Facility;
use App\User;
use App\Region;
use App\Province;
use App\City;
use App\Barangay;

class BSI_p1 extends Controller{

    function item_1_1() {
        return date('F d, Y');
    }

    function item_1_2() {
        $facility = Facility::whereFacilityCd($this->facility_cd)->first();
        $region = Region::whereRegcode($facility->address_region)->first();
        return $region->regname;

    }

    function item_1_3() {
        $facility = Facility::whereFacilityCd($this->facility_cd)->first();
        return $facility->facility_name;
    }

    function item_1_4() {
        $facility = Facility::whereFacilityCd($this->facility_cd)->first();
        return $facility->category;
    }

    function item_1_5() {
        $user = User::whereUserId($this->user_id)->first();
        return $user->user_fname." ".$user->user_mname." ".$user->user_lname;
    }

    function item_1_6() {
        $user = User::whereUserId($this->user_id)->first();
        return $user->position;
    }

    function item_1_7() {
        $user = User::whereUserId($this->user_id)->first();
        return $user->organization;
    }

    function item_1_8() {
        $user = User::whereUserId($this->user_id)->first();
        $facility = Facility::whereFacilityCd($this->facility_cd)->first();

        $province = Province::whereProvcode($user->prov)->first();
        $city = City::whereCitycode($user->citymun)->first();
        $barangay = Barangay::whereBgycode($user->brgy)->first();
        return $user->no_st_blk." ".
        ($barangay ? $barangay->bgyname : '')
        ." ".
        ($city ? $city->cityname: '').
        " ".
        ($province ? $province->provname : '');
    }

    function item_1_9() {
        $user = User::whereUserId($this->user_id)->first();
        return $user->telno;
    }

    function item_1_11() {
        $user = User::whereUserId($this->user_id)->first();
        return $user->email;
    }

    function item_1_12() {

        return $this->month."-".$this->year;
    }

    function item_2_3_1(){
        
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereNotNull('donor_sn')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereNotNull('donor_sn')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_3_2(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereNotNull('donor_sn')
                ->whereDonationType('V')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereNotNull('donor_sn')
                ->whereDonationType('V')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_4_1(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereHas('test',function($query){
                    $query->whereResult('R');
                })
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereHas('test',function($query){
                    $query->whereResult('R');
                })
                ->get();

        $mbd_without_test = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereHas('test',function($query){
                    $query->whereNull('bloodtest_no');
                })
                ->whereMhPeStat('PD')
                ->get();
                
        $walkin_without_test = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereHas('test',function($query){
                    $query->whereNull('bloodtest_no');
                })
                ->whereMhPeStat('PD')
                ->get();

        return $mbd->count()+$walkin->count()+$mbd_without_test->count()+$walkin_without_test->count();
    }

    function item_2_4_2(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereMhPeStat('TD')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereMhPeStat('TD')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function item_2_4_3(){
        return $this->item_2_4_1()+$this->item_2_4_2();
    }

}
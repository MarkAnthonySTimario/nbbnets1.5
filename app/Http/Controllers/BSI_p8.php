<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BSI_p7;
use App\Donation;
use App\Discard;

class BSI_p8 extends BSI_p7{

    function item_5_10(){
        $mbd = Donation::whereFacilityCd($this->facility_cd)
                ->where('sched_id','!=','Walk-in')
                ->whereHas('mbd',function($query){
                    $query->where('donation_dt','like',$this->year.'-'.$this->month.'%');
                })
                ->whereCollectionMethod('WB')
                ->whereCollectionStat('UNS')
                ->get();
                
        $walkin = Donation::whereFacilityCd($this->facility_cd)
                ->whereSchedId('Walk-in')
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereCollectionMethod('WB')
                ->whereCollectionStat('UNS')
                ->get();

        return $mbd->count()+$walkin->count();
    }

    function sub_item_5_11_($component_cd,$reason){
        $discards = Discard::whereFacilityCd($this->facility_cd)
                ->where('discard_dt','LIKE',$this->year.'-'.$this->month.'-%')
                ->whereComponentCd($component_cd)
                ->whereDiscardReason($reason)
                ->get();

        return $discards->count();
    }

    function sub_item_5_11__($component_cd){
        $tti = $this->sub_item_5_11_($component_cd,'TTI');
        $pro = $this->sub_item_5_11_($component_cd,'PRO');
        $sto = $this->sub_item_5_11_($component_cd,'STO');
        $tran = $this->sub_item_5_11_($component_cd,'TRAN');
        $exp = $this->sub_item_5_11_($component_cd,'EXP');
        $total = $tti + $pro + $sto + $tran + $exp;
        return [
            'tti' => $tti, 'pro' => $pro, 'sto' => $sto, 'tran' => $tran, 'exp' => $exp, 'total' => $total
        ];
    }

    function sub_item_5_11___($reason){
        $discards = Discard::whereFacilityCd($this->facility_cd)
                ->where('discard_dt','LIKE',$this->year.'-'.$this->month.'-%')
                ->whereDiscardReason($reason)
                ->get();

        return $discards->count();
    }

    function item_5_11_1(){
        return $this->sub_item_5_11__(10);
    }

    function item_5_11_2(){
        return $this->sub_item_5_11__(20);
    }

    function item_5_11_3(){
        return $this->sub_item_5_11__(30);
    }

    // Cant resolve, dont know what to do here
    function item_5_11_4(){
        return [
            'tti' => 0, 'pro' => 0, 'sto' => 0, 'tran' => 0, 'exp' => 0, 'total' => 0
        ];
    }

    function item_5_11_5(){
        return $this->sub_item_5_11__(40);
    }

    function item_5_11_6(){
        return $this->sub_item_5_11__(50);
    }

    function item_5_11_7(){
        $tti = $this->sub_item_5_11___('TTI');
        $pro = $this->sub_item_5_11___('PRO');
        $sto = $this->sub_item_5_11___('STO');
        $tran = $this->sub_item_5_11___('TRAN');
        $exp = $this->sub_item_5_11___('EXP');
        $total = $tti + $pro + $sto + $tran + $exp;
        return [
            'tti' => $tti, 'pro' => $pro, 'sto' => $sto, 'tran' => $tran, 'exp' => $exp, 'total' => $total
        ];
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BSI_p9;
use App\TransfusionRequest;

class BSI_p10 extends BSI_p9{

    function sub_item_6_6_($component_cd){
        $requests = TransfusionRequest::whereFacilityCd($this->facility_cd)
                    ->where('created_dt','like',$this->year.'-'.$this->month.'-%')
                    ->whereStatus('I')
                    ->whereHas('details',function($query) use ($component_cd){
                        $query->whereComponentCd($component_cd);
                    })->get();
        
        $units = [];
        foreach($requests as $r){
            if($r->details){
                foreach($r->details as $unit){
                    $units[] = $unit;
                }
            }
        }

        $units = collect($units);
        return $units;
    }

    function item_6_6_1(){
        return $this->sub_item_6_6_(10)->count();
    }

    function item_6_6_2(){
        return $this->sub_item_6_6_(20)->count();
    }

    // Plasma not yet included
    function item_6_6_3(){
        return $this->sub_item_6_6_(40)->count();
    }

    function item_6_6_4(){
        return $this->sub_item_6_6_(30)->count();
    }

    function item_6_6_5(){
        return $this->sub_item_6_6_(80)->count();
    }

    function item_6_6_6(){
        return $this->sub_item_6_6_(50)->count();
    }

    function item_6_9(){
        return $this->sub_item_6_6_(50)->count();
    }

    
}
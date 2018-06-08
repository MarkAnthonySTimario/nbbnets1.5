<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BSI_p5;
use App\TestResult;
use App\TestResultDetail;

class BSI_p6 extends BSI_p5{

    function sub_item_3_4_(){
        $donations = TestResult::whereFacilityCd($this->facility_cd)
                ->where('bloodtest_dt','like',$this->year.'-'.$this->month.'%')
                ->get();

        return $donations->count();
    }

    function item_3_4_1(){
        return $this->sub_item_3_4_();
    }

    function item_3_4_2(){
        return $this->sub_item_3_4_();
    }

    function item_3_4_3(){
        return $this->sub_item_3_4_();
    }

    function item_3_4_4(){
        return $this->sub_item_3_4_();
    }

    function item_3_4_5(){
        return $this->sub_item_3_4_();
    }

    function sub_item_3_5_($tti){
        $donations = TestResultDetail::whereExamCd($tti)
                ->where('created_dt','like',$this->year.'-'.$this->month.'%')
                ->whereHas('header',function($query){
                    $query->whereFacilityCd($this->facility_cd);
                })
                ->whereResultInt('R')
                ->get();

        return $donations->count();
    }

    function item_3_5_1(){
        return $this->sub_item_3_5_('HIV');
    }

    function item_3_5_2(){
        return $this->sub_item_3_5_('HBSAG');
    }

    function item_3_5_3(){
        return $this->sub_item_3_5_('HCV');
    }

    function item_3_5_4(){
        return $this->sub_item_3_5_('RPR');
    }

    function item_3_5_5(){
        return $this->sub_item_3_5_('MALA');
    }

    // unsure if can be fetch from database
    function item_3_6_1(){
        return 0;
    }

    // unsure if can be fetch from database
    function item_3_6_2(){
        return 0;
    }

    // unsure if can be fetch from database
    function item_3_6_3(){
        return 0;
    }

    // unsure if can be fetch from database
    function item_3_6_4(){
        return 0;
    }

    // unsure if can be fetch from database
    function item_3_6_5(){
        return 0;
    }

    function item_3_7(){
        $tests = $this->sub_item_3_4_();
        return [
            'HIV' => $tests,
            'HBSAG' => $tests,
            'HCV' => $tests,
            'RPR' => $tests,
            'MALA' => $tests,
            'TOTAL' => $tests
        ];
    }
}
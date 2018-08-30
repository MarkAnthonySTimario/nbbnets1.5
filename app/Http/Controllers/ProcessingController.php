<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\Processing;
use App\Blood;
use App\Component;
use App\TestResult;

class ProcessingController extends Controller
{
    function lists(Request $request){
        $sched = $request->get('sched');
        $sched_id = $sched['sched_id'];
        $facility_cd = $request->get('facility_cd');
        $donations = [];
        if($sched_id == 'Walk-in'){
            $from = $sched['from'];
            $to = $sched['to'];
            $donations = Donation::with('processing','units_min','type','test','mbd')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->whereBetween('created_dt',[$from,$to])->get();
        }else{
            $donations = Donation::with('processing','units_min','type','test','mbd')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->get();
        }

        $response = [];
        foreach($donations as $donation){
            if(!$donation->blood_bag){

            }else if(!$donation->test){
                if(!$donation->processing && $donation->units_min->count() == 0){
                    $response[] = $donation;
                }
            }else if($donation->test->result != 'R'){
                if(!$donation->processing && $donation->units_min->count() == 0){
                    $response[] = $donation;
                }
            }
        }
        return $response;
    }

    function save(Request $request){
        $donations = $request->get('donations');
        $user_id = $request->get('user_id');
        $verifier = $request->get('verifier');
        $facility_cd = $request->get('facility_cd');

        foreach($donations as $d){
            $p = new Processing;
            $p->bloodproc_no = Processing::generateNo($facility_cd);
            $p->facility_cd = $facility_cd;
            $p->bloodproc_dt = date('Y-m-d H:i:s');
            $p->donation_id = $d['donation_id'];
            $p->blood_bag = $d['blood_bag'];
            $p->created_by = $user_id;
            $p->created_dt = date('Y-m-d H:i:s');
            $p->save();

            $test = TestResult::select('donation_id','result')->whereDonationId($d['donation_id'])->first();

            foreach($d['units'] as $component_cd => $unit){
                if($unit){
                    $c = new Blood;
                    $c->donation_id = $d['donation_id'];
                    $c->component_cd = $component_cd;
                    if($d['type']){
                        $c->blood_type = $d['type']['blood_type'];
                    }
                    $c->location = $facility_cd;
                    $collection_dt = $d['sched_id'] == 'Walk-in' ? $d['created_dt'] : $d['mbd']['donation_dt'];
                    $c->expiration_dt = self::computeExpiration($component_cd,$collection_dt);
                    $c->component_vol = $unit;
                    if($test){
                        $c->comp_stat = $test->result == 'R' ? 'REA' :'FBT';
                    }else{
                        $c->comp_stat = 'FBT';
                    }
                    $c->created_by = $user_id;
                    $c->created_dt = date('Y-m-d H:i:s');
                    $c->save();
                }
            }
        }
    }

    static $components = null;

    static function computeExpiration($component_cd,$collection_dt){
        if(!self::$components){
            $components = Component::select('component_cd','comp_name','exp_interval','exp_interval_type')->whereDisableFlg('N')->get();
            foreach($components as $c){
                self::$components[$c->component_cd] = $c;
            }
        }

        switch(self::$components[$component_cd]['exp_interval_type']){
            case "M":
                $interval = "MONTH";
            break;
            case "Y":
                $interval = "YEAR";
            break;
            case "D":
            default:
                $interval = "DAY";
        }

        $query = "SELECT DATE_ADD('".$collection_dt."',INTERVAL ".self::$components[$component_cd]['exp_interval']." ".$interval.") as `expiration_dt`";

        return str_replace('00:00:00','23:59:00',\DB::select(\DB::raw($query))[0]->expiration_dt);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\Blood;
use DB;

class ReleaseToInventoryController extends Controller
{
    function list(Request $request){
        $sched = $request->get('sched');
        $sched_id = $sched['sched_id'];
        $facility_cd = $request->get('facility_cd');
        $component_cd = $request->get('component_cd');

        $donations = [];
        if($sched_id == 'Walk-in'){
            $from = $sched['from'];
            $to = $sched['to'];
            $donations = Donation::with('type','labels','processing','test','units','donor_min','labels')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereNotNull('blood_bag')->whereSchedId($sched_id)->whereBetween('created_dt',[$from,$to])->get();
        }else{
            $donations = Donation::with('type','labels','processing','test','units','donor_min','labels')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereNotNull('blood_bag')->whereSchedId($sched_id)->get();
        }

        $response = [];
        foreach($donations as $donation){
            if($donation->test){
                if($donation->test->result != 'R'){
                    $response[] = $donation;
                }
            }
        }
        return $response;
    }

    function save(Request $request){
        $facility_cd = $request->get('facility_cd');
        $selected = $request->get('selected');
        $user_id = $request->get('user_id');
        $verifier = $request->get('verifier');
        
        foreach($selected as $selection){
            DB::statement(DB::raw("UPDATE component SET comp_stat = 'AVA' WHERE donation_id = '".$selection['donation_id']."' AND component_cd = '".$selection['component_cd']."'"));
        }

    }
 }

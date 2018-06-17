<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\TypingResult;
use App\Blood;

class TypingController extends Controller
{
    function lists(Request $request){
        $sched = $request->get('sched');
        $sched_id = $sched['sched_id'];
        $facility_cd = $request->get('facility_cd');
        $donations = [];
        if($sched_id == 'Walk-in'){
            $from = $sched['from'];
            $to = $sched['to'];
            $donations = Donation::with('type','test')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->whereBetween('created_dt',[$from,$to])->get();
        }else{
            $donations = Donation::with('type','test')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->get();
        }

        $response = [];
        foreach($donations as $donation){
            if(!$donation->blood_bag){

            }else if(!$donation->test){
                if(!$donation->type){
                    $response[] = $donation->donation_id;
                }
            }else if($donation->test->result != 'R'){
                if(!$donation->type){
                    $response[] = $donation->donation_id;
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
            $t = new TypingResult;
            $t->bloodtyping_no = TypingResult::generateNo($facility_cd);
            $t->facility_cd = $facility_cd;
            $t->bloodtyping_dt = date('Y-m-d H:i:s');
            $t->donation_id = $d['donation_id'];
            $t->blood_type = $d['abo'].' '.$d['rh'];
            $t->reviewed_endorsed_by = $verifier['username'];
            $t->created_by = $user_id;
            $t->created_dt = date('Y-m-d H:i:s');
            $t->save();

            $units = Blood::whereDonationId($d['donation_id'])->get();
            foreach($units as $unit){
                $unit->blood_type = $d['abo'].' '.$d['rh'];
                $unit->save();
            }
        }
    }
}

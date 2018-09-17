<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\Blood;
use App\Discard;
use App\SharedUnscreenedUnit;

class DiscardController extends Controller
{
    function lists(Request $request){
        $sched = $request->get('sched');
        $sched_id = $sched['sched_id'];
        $donation_id = $request->get('donation_id');
        $component_cd = $request->get('component_cd');
        $facility_cd = $request->get('facility_cd');
        $donations = [];

        if($sched_id){
            if($sched_id == 'Shared'){
                $donations = SharedUnscreenedUnit::with('donation','donation.type','donation.test','donation.mbd','donation.units','donation.discards','donation.labels')
                    ->select('donation_id')
                    ->whereSharedFacilityCd($facility_cd)
                    ->whereNotNull('registered_by')
                    ->groupBy('donation_id');
            }else if($sched_id == 'Walk-in'){
                $from = $sched['from'];
                $to = $sched['to'];
                $donations = Donation::with('type','test','mbd','units','discards','labels')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->whereBetween('created_dt',[$from,$to]);
            }else{
                $donations = Donation::with('type','test','mbd','units','discards','labels')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id);
            }

            if($donation_id){
                $donations->whereDonationId($donation_id);
            }
        }else{
            if($donation_id){
                $donations = Donation::with('type','test','mbd','units','discards')->whereFacilityCd($facility_cd)->whereDonationId($donation_id)->get();
            }
        }

        
        $donations = $donations->get();

        if($sched_id == 'Shared'){
            $old = $donations;
            $donations = [];
            foreach($old as $r){
                $donations[] = $r->donation;
            }
        }
        
        $response = [];

        foreach($donations as $donation){
            if(!count($donation->units)){

            }else if(!$donation->blood_bag){
                
            }else if($donation->test){
                if($donation->test->result != "R"){
                    if(!$component_cd){
                        $response[] = $donation;
                    }else{
                        if($donation->units){
                            $hasUnit = $donation->units->first(function($unit) use ($component_cd){
                                return $unit->component_cd == $component_cd;
                            });
                            if($hasUnit){
                                $response[] = $donation;
                            }
                        }
                    }
                }
            }else{
                if(!$component_cd){
                    $response[] = $donation;
                }else{
                    if($donation->units){
                        $hasUnit = $donation->units->first(function($unit) use ($component_cd){
                            return $unit->component_cd == $component_cd;
                        });
                        if($hasUnit){
                            $response[] = $donation;
                        }
                    }
                }
            }
        }
        return $response;
    }

    function save(Request $request){
        $units = $request->get('units');
        $reason = $request->get('reason');
        $remarks = $request->get('remarks');
        $verifier = $request->get('verifier');
        $facility_cd = $request->get('facility_cd');
        $user_id = $request->get('user_id');

        foreach($units as $unit){
            $exist = Discard::whereDonationId($unit['donation_id'])
                        ->whereComponentCd($unit['component_cd'])
                        ->first();
            if(!$exist){
                $discard = new Discard;
                $discard->facility_cd = $facility_cd;
                $discard->discard_dt = date('Y-m-d H:i:s');
                $discard->discard_by = $user_id;
                $discard->reviewed_by = $verifier['username'];
                $discard->donation_id = $unit['donation_id'];
                $discard->component_cd = $unit['component_cd'];
                $discard->discard_reason = $reason;
                $discard->remarks = $remarks;
                $discard->save();
            }

            $unit = Blood::whereDonationId($unit['donation_id'])
                    ->whereComponentCd($unit['component_cd'])
                    ->first();
            $unit->comp_stat = 'DIS';
            $unit->save();
        }
    }
}

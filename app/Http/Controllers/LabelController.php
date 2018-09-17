<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\Label;
use App\SharedUnscreenedUnit;

class LabelController extends Controller
{
    function lists(Request $request){
        $sched = $request->get('sched');
        $sched_id = $sched['sched_id'];
        $facility_cd = $request->get('facility_cd');
        $component_cd = $request->get('component_cd');

        $donations = [];
        if($sched_id == 'Shared'){
            $donations = SharedUnscreenedUnit::with('donation','donation.type','donation.test','donation.mbd','donation.units','donation.discards','donation.labels','donation.processing','donation.donor_min')
                ->select('donation_id')
                ->whereSharedFacilityCd($facility_cd)
                ->whereNotNull('registered_by')
                ->groupBy('donation_id')->get();
        }else if($sched_id == 'Walk-in'){
            $from = $sched['from'];
            $to = $sched['to'];
            $donations = Donation::with('type','labels','processing','test','additionaltest','units','donor_min')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereNotNull('blood_bag')->whereSchedId($sched_id)->whereBetween('created_dt',[$from,$to])->get();
        }else{
            $donations = Donation::with('type','labels','processing','test','additionaltest','units','donor_min')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereNotNull('blood_bag')->whereSchedId($sched_id)->get();
        }

        if($sched_id == 'Shared'){
            $old = $donations;
            $donations = [];
            foreach($old as $r){
                $donations[] = $r->donation;
            }
        }

        $response = [];
        foreach($donations as $donation){
            if(!$donation->test){
                if($donation->additionaltest){
                    $ad = $donation->additionaltest;
                    if($ad->antibody != 'R' && $ad->nat != 'R' && $ad->zika != 'R'){
                        $response[] = $donation;
                    }
                }else{
                    $response[] = $donation;
                }
            }else if($donation->test->result != 'R'){
                if($donation->additionaltest){
                    $ad = $donation->additionaltest;
                    if($ad->antibody != 'R' && $ad->nat != 'R' && $ad->zika != 'R'){
                        $response[] = $donation;
                    }
                }else{
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
            $label = new Label;
            $label->label_no = Label::generateNo($facility_cd);
            $label->facility_cd = $facility_cd;
            $label->label_dt = date('Y-m-d H:i:s');
            $label->label_by = $user_id;
            $label->donation_id = $selection['donation_id'];
            $label->component_cd = $selection['component_cd'];
            $label->reprint_count = 0;
            $label->reason = null;
            $label->save();
        }

    }

    
    
}

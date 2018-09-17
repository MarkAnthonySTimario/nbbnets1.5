<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdditionalTest;
use App\Donation;
use App\SharedUnscreenedUnit;

class AdditionalTestController extends Controller
{

    function listsAntibody(Request $request){
        $sched = $request->get('sched');
        $sched_id = $sched['sched_id'];
        $facility_cd = $request->get('facility_cd');
        $donations = [];

        if($sched_id == 'Shared'){
            $donations = SharedUnscreenedUnit::with('donation','donation.type','donation.additionaltest')
                ->select('donation_id')
                ->whereSharedFacilityCd($facility_cd)
                ->whereNotNull('registered_by')
                ->groupBy('donation_id')->get();
        }else if($sched_id == 'Walk-in'){
            $from = $sched['from'];
            $to = $sched['to'];
            $donations = Donation::with('type','additionaltest')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->whereBetween('created_dt',[$from,$to])->get();
        }else{
            $donations = Donation::with('type','additionaltest')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->get();
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
            if(!$donation->blood_bag){

            }else if(!$donation->additionaltest){
                $response[] = $donation->donation_id;
            }else if(!$donation->additionaltest->antibody){
                $response[] = $donation->donation_id;
            }
        }
        return $response;
    }

    function saveAntibody(Request $request){
        $donations = $request->get('donations');
        $user_id = $request->get('user_id');
        $verifier = $request->get('verifier');
        $facility_cd = $request->get('facility_cd');

        foreach($donations as $d){
            $t = AdditionalTest::whereDonationId($d['donation_id'])->first();
            
            if(!$t){
                $t = new AdditionalTest;
                $t->donation_id = $d['donation_id'];
            }

            $t->antibody = $d['antibody'];
            $t->antibody_by = $user_id;
            $t->antibody_verifier = $verifier['username'];
            $t->save();

            if($d['antibody'] == 'R'){
                FlagReactiveController::flagReactive($d['donation_id']);
            }else{
                FlagReactiveController::flagNonReactive($d['donation_id'],$facility_cd);
            }

        }
    }

    function listsNat(Request $request){
        $sched = $request->get('sched');
        $sched_id = $sched['sched_id'];
        $facility_cd = $request->get('facility_cd');
        $donations = [];
        if($sched_id == 'Shared'){
            $donations = SharedUnscreenedUnit::with('donation','donation.type','donation.additionaltest')
                ->select('donation_id')
                ->whereSharedFacilityCd($facility_cd)
                ->whereNotNull('registered_by')
                ->groupBy('donation_id')->get();
        }else if($sched_id == 'Walk-in'){
            $from = $sched['from'];
            $to = $sched['to'];
            $donations = Donation::with('type','additionaltest')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->whereBetween('created_dt',[$from,$to])->get();
        }else{
            $donations = Donation::with('type','additionaltest')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->get();
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
            if(!$donation->blood_bag){

            }else if(!$donation->additionaltest){
                $response[] = $donation->donation_id;
            }else if(!$donation->additionaltest->nat){
                $response[] = $donation->donation_id;
            }
        }
        return $response;
    }

    function saveNat(Request $request){
        $donations = $request->get('donations');
        $user_id = $request->get('user_id');
        $verifier = $request->get('verifier');
        $facility_cd = $request->get('facility_cd');

        foreach($donations as $d){
            $t = AdditionalTest::whereDonationId($d['donation_id'])->first();
            if(!$t){
                $t = new AdditionalTest;
                $t->donation_id = $d['donation_id'];
            }
            $t->nat = $d['nat'];
            $t->nat_by = $user_id;
            $t->nat_verifier = $verifier['username'];
            $t->save();

            if($d['nat'] == 'R'){
                FlagReactiveController::flagReactive($d['donation_id']);
            }else{
                FlagReactiveController::flagNonReactive($d['donation_id'],$facility_cd);
            }

        }
    }

    function listsZika(Request $request){
        $sched = $request->get('sched');
        $sched_id = $sched['sched_id'];
        $facility_cd = $request->get('facility_cd');
        $donations = [];

        if($sched_id == 'Shared'){
            $donations = SharedUnscreenedUnit::with('donation','donation.type','donation.additionaltest')
                ->select('donation_id')
                ->whereSharedFacilityCd($facility_cd)
                ->whereNotNull('registered_by')
                ->groupBy('donation_id')->get();
        }else if($sched_id == 'Walk-in'){
            $from = $sched['from'];
            $to = $sched['to'];
            $donations = Donation::with('type','additionaltest')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->whereBetween('created_dt',[$from,$to])->get();
        }else{
            $donations = Donation::with('type','additionaltest')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->get();
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
            if(!$donation->blood_bag){

            }else if(!$donation->additionaltest){
                $response[] = $donation->donation_id;
            }else if(!$donation->additionaltest->zika){
                $response[] = $donation->donation_id;
            }
        }
        return $response;
    }

    function saveZika(Request $request){
        $donations = $request->get('donations');
        $user_id = $request->get('user_id');
        $verifier = $request->get('verifier');
        $facility_cd = $request->get('facility_cd');

        foreach($donations as $d){
            $t = AdditionalTest::whereDonationId($d['donation_id'])->first();
            if(!$t){
                $t = new AdditionalTest;
                $t->donation_id = $d['donation_id'];
            }
            $t->zika = $d['zika'];
            $t->zika_by = $user_id;
            $t->zika_verifier = $verifier['username'];
            $t->save();

            if($d['zika'] == 'R'){
                FlagReactiveController::flagReactive($d['donation_id']);
            }else{
                FlagReactiveController::flagNonReactive($d['donation_id'],$facility_cd);
            }

        }
    }
}

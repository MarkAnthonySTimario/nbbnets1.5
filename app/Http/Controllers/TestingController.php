<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\TestResult;
use App\TestResultDetail;
use App\Exam;
use App\Donor;
use App\Component;
use App\Blood;
use App\Discard;
use App\Http\Controllers\FlagReactiveController;
use App\SharedUnscreenedUnit;

class TestingController extends Controller
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
        }else if($sched_id == 'Shared'){
            $donations = SharedUnscreenedUnit::with('test')
                ->whereSharedFacilityCd($facility_cd)
                ->whereNotNull('registered_by')
                ->get();
        }else{
            $donations = Donation::with('type','test')->whereNotNull('donation_id')->whereFacilityCd($facility_cd)->whereSchedId($sched_id)->get();
        }


        $response = [];

        if($sched_id == 'Shared'){
            foreach($donations as $donation){
                if(!$donation->test){
                    $response[] = $donation->donation_id;
                }
            }
            $response = array_unique($response);
            $out = [];
            foreach($response as $row){
                $out[] = $row;
            }
            return $out;
        }

        foreach($donations as $donation){
            if(!$donation->blood_bag){

            }else if(!$donation->test){
                $response[] = $donation->donation_id;
            }
        }
        return $response;
    }

    function save(Request $request){
        $donations = $request->get('donations');
        $user_id = $request->get('user_id');
        $verifier = $request->get('verifier');
        $facility_cd = $request->get('facility_cd');
        $exams = Exam::whereDisableFlg('N')->pluck('exam_name','exam_cd');

        foreach($donations as $d){
            $bloodtest_no = TestResult::generateNo($facility_cd);
            $t = new TestResult;
            $t->facility_cd = $facility_cd;
            $t->bloodtest_no = $bloodtest_no;
            $t->bloodtest_dt = date('Y-m-d H:i:s');
            $t->donation_id = $d['donation_id'];

            $fail = 0;
            foreach($exams as $exam_cd => $exam_name){
                if($d[$exam_cd] == 'R'){
                    $fail++;
                }
            }

            $t->result = $fail ? 'R' : 'N';
            $t->created_by = $user_id;
            $t->created_dt = date('Y-m-d H:i:s');
            $t->updated_by = $verifier['username'];

            $t->save();

            foreach($exams as $exam_cd => $exam_name){
                $t2 = new TestResultDetail;
                $t2->bloodtest_no = $bloodtest_no;
                $t2->donation_id = $d['donation_id'];
                $t2->exam_cd = $exam_cd;
                $t2->result_int = $d[$exam_cd] == 'N' ? 'n' : 'r';
                $t2->created_by = $user_id;
                $t2->created_dt = date('Y-m-d H:i:s');
                $t2->save();
            }

            if($fail){
                FlagReactiveController::flagReactive($d['donation_id']);
            }else{
                FlagReactiveController::flagNonReactive($d['donation_id'],$facility_cd);
            }

        }
    }

    function forconfirmatory(Request $request){
        $facility_cd = $request->get('facility_cd');
        $units = Blood::whereLocation($facility_cd)->whereCompStat('QUA')->select('component_cd','donation_id')->get();

        return $units;
    }

    function discard(Request $request){
        $units = $request->get('units');
        $verifier = $request->get('verifier');
        $user_id = $request->get('user_id');
        $facility_cd = $request->get('facility_cd');

        foreach($units as $unit){
            $exist = Discard::whereDonationId($unit['donation_id'])->whereComponentCd($unit['component_cd'])->first();
            if(!$exist){
                $d = new Discard;
                $d->facility_cd = $facility_cd;
                $d->discard_dt = date('Y-m-d H:i:s');
                $d->discard_by = $user_id;
                $d->reviewed_by = $verifier['username'];
                $d->donation_id = $unit['donation_id'];
                $d->component_cd = $unit['component_cd'];
                $d->discard_reason = 'TTI';
                $d->save();
            }

            $d = Blood::whereDonationId($unit['donation_id'])->whereComponentCd($unit['component_cd'])->first();
            $d->comp_stat = 'DIS';
            $d->save();
        }
    }
}

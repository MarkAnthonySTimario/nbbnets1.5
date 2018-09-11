<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\Http\Controllers\FlagReactiveController;

class DonationController extends Controller
{
    function newWalkIn(Request $request){
        $donation_id = $request->get('donation_id');
        $exists = Donation::whereDonationId($donation_id)->first();
        if($exists){
            return 'donation_id_error';
        }
        $facility_cd = $request->get('facility_cd');
        $donation = new Donation;
        $donation->seqno = Donation::generateSeqno($facility_cd);
        $donation->donation_id = $donation_id;
        $donation->approved_by = $request->get('approvedBy');
        $donation->donor_sn = $request->get('donor')['seqno'];
        $donation->sched_id = 'Walk-in';
        $donation->pre_registered = 'N';
        $donation->donation_type = $request->get('donation_type');
        $donation->collection_method = $request->get('collection_method');
        $donation->facility_cd = $facility_cd;
        $donation->mh_pe_stat = $request->get('mh_pe_stat');
        $donation->mh_pe_deferral = $request->get('mh_pe_deferral');
        $donation->mh_pe_question = $request->get('mh_pe_question');
        $donation->mh_pe_remark = $request->get('mh_pe_remark');
        $donation->collection_type = $request->get('collection_type');
        $donation->collection_stat = $request->get('collection_stat');
        $donation->coluns_res = $request->get('coluns_res');
        $donation->created_by = $request->get('user_id');
        $donation->created_dt = date('Y-m-d H:i:s');
        $donation->save();
        return $donation;
    }

    function walkinAssignDonor(Request $request){
        $donation_id = $request->get('donation_id');
        $facility_cd = $request->get('facility_cd');
        $user_id = $request->get('user_id');
        $donor_sn = $request->get('seqno');
        $donation = Donation::whereDonationId($donation_id)->first();
        $donation->donor_sn = $donor_sn;
        $donation->updated_by = $user_id;
        $donation->updated_dt = date('Y-m-d H:i:s');
        $donation->save();

        FlagReactiveController::flag($donation_id,$facility_cd);

        return $donation;
    }

    function mbdNewDonor(Request $request){
        $sched_id = $request->get('sched_id');
        $seqno = $request->get('seqno');
        $facility_cd = $request->get('facility_cd');
        $user_id = $request->get('user_id');

        $donation = new Donation;
        $donation->seqno = Donation::generateSeqno($facility_cd);
        $donation->donor_sn = $seqno;
        $donation->sched_id = $sched_id;
        $donation->pre_registered = 'N';
        $donation->donation_type = 'V';
        $donation->collection_method = 'WB';
        $donation->facility_cd = $facility_cd;
        $donation->created_by = $user_id;
        $donation->created_dt = date('Y-m-d H:i:s');
        $donation->save();

        return $donation;
    }

    function mbdAssignDonor(Request $request){
        $donation = Donation::whereDonationId($request->get('donation_id'))->firstOrFail();
        $donation->donor_sn = $request->get('seqno');
        $donation->updated_by = $request->get('user_id');
        $donation->updated_dt = date('Y-m-d H:i:s');
        $donation->save();

        FlagReactiveController::flag($request->get('donation_id'),$request->get('facility_cd'));

        return $donation;
    }

    function donationRemove(Request $request){
        $seqno = $request->get('seqno');
        $donation = Donation::whereSeqno($seqno)->first();
        $donation->delete();
        return $seqno;
    }

    function checkDonationIDs(Request $request){
        $donation_ids = $request->get('donation_ids');
        $errors = [];
        foreach($donation_ids as $donation_id){
            if($donation_id){
                $donation = Donation::whereDonationId($donation_id)->first();
                if($donation){
                    $errors[] = ["Donation ID ".$donation_id." is already taken."];
                }
            }
        }

        return $errors;
    }

    function registerCheckDonationIDs(Request $request){
        $donation_ids = $request->get('donation_ids');
        $errors = [];
        foreach($donation_ids as $donation_id){
            if($donation_id){
                $donation = Donation::whereDonationId($donation_id)->first();
                if($donation){
                    if($donation->blood_bag){
                        $errors[] = ["Donation ID ".$donation_id." is already registered"];
                    }else if($donation->mh_pe_stat != 'A' || $donation->collection_stat != 'COL'){
                        $errors[] = ["Donation ID ".$donation_id." has unsuccesfull collection result."];
                    }
                }
            }
        }

        return $errors;
    }

    function updateDonationDetails(Request $request){
        $donations = $request->get('donations');
        $user = $request->get('user');
        foreach($donations as $donation){
            $d = Donation::whereSeqno($donation['seqno'])->first();
            $d->donation_id = $donation['donation_id'];
            $d->updated_by = $user['user_id'];
            $d->updated_dt = date('Y-m-d H:i:s');
            $d->donation_stat = $donation['donation_stat'];
            $d->mh_pe_stat = $donation['donation_stat'];
            $d->mh_pe_deferral = $donation['mh_pe_deferral'];
            $d->mh_pe_question = $donation['mh_pe_question'];
            $d->mh_pe_remark = $donation['mh_pe_remark'];
            $d->collection_type = $donation['collection_type'];
            $d->collection_stat = $donation['collection_stat'];
            $d->coluns_res = $donation['coluns_res'];
            $d->save();
        }

        return $donation;
    }

    function walkin(Request $request){
        $facility_cd = $request->get('facility_cd');
        $date = $request->get('search');
        return Donation::with('donor')
        ->whereFacilityCd($facility_cd)
        ->whereSchedId('Walk-in')
        ->where('created_dt','like',$date.'%')
        ->orderBy('created_dt','desc')->get();
    }

    function register(Request $request){
        $sched_id = $request->get('sched_id');
        $user_id = $request->get('user_id');
        $facility_cd = $request->get('facility_cd');
        $verifier = $request->get('verifier');
        $rows = $request->get('rows');

        foreach($rows as $row){
            $donation = Donation::whereDonationId($row['donation_id'])->first();
            if(!$donation){
                $d = new Donation;
                $d->seqno = Donation::generateSeqno($facility_cd);
                $d->donation_id = $row['donation_id'];
                $d->sched_id = $sched_id;
                $d->donation_type = 'V';
                $d->collection_method = 'WB';
                $d->donation_stat = 'A';
                $d->mh_pe_stat = 'A';
                $d->collection_stat = 'COL';
                $d->blood_bag = $row['bag'];
                $d->created_by = $user_id;
                $d->created_dt = date('Y-m-d h:i:s');
                $d->facility_cd = $facility_cd;
                $d->pre_registered = 'Y';
                $d->save();
            }else{
                $donation->blood_bag = $row['bag'];
                $donation->updated_by = $user_id;
                $donation->updated_dt = date('Y-m-d H:i:s');
                $donation->save();
            }
        }
    }

    function updateChildRecords($donation){

        // if updates include null donor, previous donor should be revalidated

        // if updates includes a donor, validate new donor and revalidate previous (if present)
        
        // revalidating a donor - check all donations if has derferred records or reactive test

        // if donation_stat is REA update components to quarantened , update donor to deferred

        // affected tables includes donor_log, component and donor
        


    }
}

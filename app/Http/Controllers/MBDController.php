<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MBD;
use App\Agency;
use App\Donation;

class MBDController extends Controller
{
    function search(Request $request){
        $search = $request->get('search');
        $facility_cd = $request->get('facility_cd');

        return MBD::whereFacilityCd($facility_cd)
        ->select('sched_id','agency_name','donation_dt')
        ->where('agency_name','like','%'.$search.'%')
        ->get();
    }

    function info(Request $request,$sched_id){
        return MBD::with('donations','donations.donor','donations.processing')->whereSchedId($sched_id)->firstOrFail();
        // return MBD::whereSchedId($sched_id)->firstOrFail();
    }

    function donations(Request $request,$sched_id){
        return Donation::with('donor','processing')->whereSchedId($sched_id)->get();
    }

    function shortInfo(Request $request,$sched_id){
        return MBD::select('agency_name','donation_dt')->whereSchedId($sched_id)->firstOrFail();
    }

    function create(Request $request){
        $mbd = new MBD;
        $mbd->sched_id = $this->generateSchedID($request->get('facility_cd'));
        $mbd->agency_cd = $request->get('agency_cd');
        $mbd->agency_name = $request->get('agency_name');
        $mbd->donation_dt = $request->get('donation_dt');
        $mbd->remarks = $request->get('remarks');
        $mbd->facility_cd = $request->get('facility_cd');
        $mbd->created_by = $request->get('user_id');
        $mbd->created_dt = date('Y-m-d H:i:s');
        $mbd->save();
        return $mbd;
    }

    function generateSchedID($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = MBD::select('sched_id')->whereFacilityCd($facility_cd)->orderBy('sched_id','desc')->first()->sched_id;
            if(!$max){
                return $facility_cd.date('Y').str_pad('1',5,'0',STR_PAD_LEFT);
            }
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);
        $isExists = MBD::whereSchedId($new)->first();
        if($isExists){
            $i++;
            return $this->generateSchedID($facility_cd,$i,$max);
        }

        return $new;
    }

    function agencyStartWith(Request $request){
        $facility_cd = $request->get('facility_cd');
        $letter = $request->get('letter');
        return Agency::select('agency_cd','agency_name','facility_cd')
                ->whereFacilityCd($facility_cd)
                ->where('agency_name','like',$letter.'%')
                ->get();
    }

    function agencyMBDs($agency_cd){
        return MBD::select('sched_id','agency_cd','agency_name','donation_dt')->whereAgencyCd($agency_cd)->get();
    }

    function report(Request $request){
        $sched = $request->get('sched');
        $facility_cd = $request->get('facility_cd');
        
        $donations = Donation::with('donor','donor.region','donor.province','donor.city','donor.barangay','test','type')->whereSchedId($sched['sched_id']);
        if($sched['sched_id'] == 'Walk-in'){
            $donations->whereFacilityCd($facility_cd);
            $donations->whereBetween('created_dt',[$sched['from'],$sched['to']]);
        }
        return $donations->get();
    }
}

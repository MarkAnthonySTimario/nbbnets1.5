<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SharedUnscreenedUnit;
use App\Facility;
use App\Blood;
use DB;

class ShareUnscreenedUnitController extends Controller
{
    function share(Request $r){
        $facility_cd = $r->get('facility_cd');
        $shared_facility_cd = $r->get('shared_facility_cd');
        $units = $r->get('units');
        $user_id = $r->get('user_id');
        $verifier = $r->get('verifier');

        foreach($units as $unit){
            DB::statement(DB::raw("UPDATE component SET location=  '".$shared_facility_cd."' WHERE donation_id = '".$unit['donation_id']."' AND component_cd = '".$unit['component_cd']."'"));

            $s =  new SharedUnscreenedUnit;
            $s->facility_cd = $facility_cd;
            $s->shared_facility_cd = $shared_facility_cd;
            $s->donation_id  = $unit['donation_id'];
            $s->component_cd = $unit['component_cd'];
            $s->shared_dt = date('Y-m-d');
            $s->shared_by = $user_id;
            $s->approved_by = $verifier['username'];
            $s->save();

        }
    }

    function facilities(){
        return Facility::select('facility_cd','facility_name')->whereDisableFlg('N')->get();
    }

    function unscreenedList($facility_cd){
        $bloods = Blood::with('test')->select('donation_id','component_cd')
            ->whereLocation($facility_cd)
            // ->where('donation_id','like','NVBSP'.date('Y').'%')
            ->where('expiration_dt','>=',date('Y-m-d H:i:s'))
            ->get();

        $out = [];

        foreach($bloods as $blood){
            if(!$blood->test){
                $out[] = $blood;
            }
        }

        return $out;

    }
}

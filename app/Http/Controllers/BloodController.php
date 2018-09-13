<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blood;
use DB;

class BloodController extends Controller
{
    function lists(Request $request){
        $facility_cd = $request->get('facility_cd');
        $available = Blood::select('blood_type','donation_id','component_vol','expiration_dt','component_cd')
                ->with('donation_min','donation_min.mbd_min','component_min')
                ->whereLocation($facility_cd)
                ->whereCompStat('AVA')
                ->where('expiration_dt','>=',date('Y-m-d'))
                ->orderBy('expiration_dt','asc')
                ->get();

        return $available;
    }

    function getAliqoutes($donation_id, $component_cd){
        return Blood::whereSourceDonationId($donation_id)->whereComponentCd($component_cd)->get();
    }

    function getUnitDetails($donation_id, $component_cd){
        return Blood::whereDonationId($donation_id)->whereComponentCd($component_cd)->first();
    }

    function makeAliqoute(Request $r){
        $donation_id = $r->get('donation_id');
        $component_cd = $r->get('component_cd');
        $facility_cd = $r->get('facility_cd');
        $user_id = $r->get('user_id');
        $new_aliqoute_volume = $r->get('new_aliqoute_volume');

        $blood = Blood::whereLocation($facility_cd)->whereDonationId($donation_id)->whereComponentCd($component_cd)->first();
        $blood->component_vol = $blood->component_vol - $new_aliqoute_volume;
        $blood->save();

        $aliqoutes = Blood::whereSourceDonationId($donation_id)->whereComponentCd($component_cd)->get();
        $new_donation_id = $donation_id.'-'.str_pad($aliqoutes->count()+1,2,'0',STR_PAD_LEFT);

        $new = new Blood;
        $new->donation_id = $new_donation_id;
        $new->source_donation_id = $donation_id;
        $new->aliqoute_by = $user_id;
        $new->aliqoute_dt = date('Y-m-d H:i:s');
        $new->component_cd = $component_cd;
        $new->blood_type = $blood->blood_type;
        $new->location = $facility_cd;
        $new->expiration_dt = $blood->expiration_dt;
        $new->component_vol = $new_aliqoute_volume;
        $new->comp_stat = 'AVA';
        $new->created_dt = date('Y-m-d H:i:s');
        $new->created_by = $user_id;
        $new->save();
    }
}

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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Agency;

class AgencyController extends Controller
{
    function agencies(Request $request){
        $facility_cd = $request->get('facility_cd');
        $search = $request->get('search');

        return Agency::with('region','province','city','barangay')->whereFacilityCd($facility_cd)->where('agency_name','like','%'.$search.'%')->get();
    }

    function info(Request $request,$agency_cd){
        return Agency::with('region','province','city','barangay')->whereAgencyCd($agency_cd)->firstOrFail();
    }

    function update(Request $request,$agency_cd){
        // return $request->all();

        $agency = Agency::whereAgencyCd($agency_cd)->firstOrFail();

        foreach($agency->toArray() as $key => $val){
            $agency->$key = $request->get($key);
        }
        $agency->updated_dt = date('Y-m-d H:i:s');
        $agency->save();

        return $agency;
    }

    function create(Request $request){
        
        $agency_cd = $this->generateAgencyCd(13006);

        $a = new Agency();
        $a->agency_cd = $agency_cd;
        $a->agency_name = $request->get('agency_name');
        $a->facility_cd = $request->get('facility_cd');
        $a->owner = $request->get('owner');
        $a->contact_person = $request->get('contact_person');
        $a->designation = $request->get('designation');
        $a->adg_no_st_blk = $request->get('adg_no_st_blk');
        $a->adg_bgy = $request->get('adg_bgy');
        $a->adg_city = $request->get('adg_city');
        $a->adg_prov = $request->get('adg_prov');
        $a->adg_region = $request->get('adg_region');
        $a->adg_zip = $request->get('adg_zip');
        $a->adg_tel = $request->get('adg_tel');
        $a->adg_fax = $request->get('adg_fax');
        $a->email_ad = $request->get('email_ad');
        $a->mobile_tel = $request->get('mobile_tel');
        $a->disable_flg = 'N';
        $a->created_dt = date('Y-m-d H:i:s');
        $a->created_by = $request->get('created_by');
        
        $a->save();

        return $agency_cd;
    }

    function generateAgencyCd($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = Agency::select('agency_cd')->whereFacilityCd($facility_cd)->orderBy('agency_cd','desc')->first()->agency_cd;
            if(!$max){
                return $facility_cd.str_pad('1',5,'0',STR_PAD_LEFT);
            }
        }

        $new = $max+$i;
        $isExists = Agency::whereAgencyCd($new)->first();
        if($isExists){
            $i++;
            return $this->generateAgencyCd($facility_cd,$i,$max);
        }

        return $new;
    }

    function test(){
        $agency_cd = $this->generateAgencyCd(13006);

        $a = new Agency;
        $a->agency_cd = $agency_cd;
        $a->agency_name = $request->get('');
        $a->owner = $request->get('owner');
        $a->contact_person = $request->get('contact_person');
        $a->designation = $request->get('designation');
        $a->adg_no_st_blk = $request->get('adg_no_st_blk');
        $a->adg_bgy = $request->get('adg_bgy');
        $a->adg_city = $request->get('adg_city');
        $a->adg_prov = $request->get('adg_prov');
        $a->adg_region = $request->get('adg_region');
        $a->adg_zip = $request->get('adg_zip');
        $a->adg_tel = $request->get('adg_tel');
        $a->adg_fax = $request->get('adg_fax');
        $a->email_ad = $request->get('email_ad');
        $a->mobile_tel = $request->get('mobile_tel');
        $a->disable_flg = 'N';
        $a->created_dt = date('Y-m-d H:i:s');
        $a->agency_name = $request->get('created_by');
        
        $a->save();

        return $a;
    }

}

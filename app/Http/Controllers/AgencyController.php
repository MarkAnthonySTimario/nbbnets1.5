<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Agency;
use App\MBD;

class AgencyController extends Controller
{
    function agencies(Request $request){
        $facility_cd = $request->get('facility_cd');
        $search = $request->get('search');

        $agencies = Agency::whereFacilityCd($facility_cd)->with('region','province','city','barangay')->where('agency_name','like','%'.$search.'%')->get();

        foreach($agencies as $i => $agency){
            $agencies[$i] = $this->replaceNye($agency);
        }

        return $agencies;    
    }
    
    private function replaceNye($agency){
        if($agency->region){
            $agency->region->regname = str_replace("??","Ñ",$agency->region->regname);
            $agency->region->regname = str_replace("Â","Ñ",$agency->region->regname);
        }

        if($agency->province){
            $agency->province->provname = str_replace("??","Ñ",$agency->province->provname);
            $agency->province->provname = str_replace("Â","Ñ",$agency->province->provname);
        }

        if($agency->city){
            $agency->city->cityname = str_replace("??","Ñ",$agency->city->cityname);
            $agency->city->cityname = str_replace("Â","Ñ",$agency->city->cityname);
        }

        if($agency->barangay){
            $agency->barangay->bgyname = str_replace("??","Ñ",$agency->barangay->bgyname);
            $agency->barangay->bgyname = str_replace("Â","Ñ",$agency->barangay->bgyname);
        }
        
        return $agency;
    }

    function info(Request $request,$agency_cd){
        return Agency::with('region','province','city','barangay')->whereAgencyCd($agency_cd)->firstOrFail();
    }

    function update(Request $request,$agency_cd){
        // return $request->all();

        $agency = Agency::whereAgencyCd($agency_cd)->firstOrFail();

        if($agency->agency_name != $request->get('agency_name')){
            MBD::whereAgencyCd($agency_cd)->update(['agency_name' => $request->get('agency_name')]);
        }

        foreach($agency->toArray() as $key => $val){
            $agency->$key = $request->get($key);
        }


        $agency->updated_dt = date('Y-m-d H:i:s');
        $agency->save();

        return $agency;
    }

    function create(Request $request){

        $facility_cd = $request->get('facility_cd');
        
        $agency_cd = Agency::generateAgencyCd($facility_cd);

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

}

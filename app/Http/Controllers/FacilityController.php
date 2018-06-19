<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;

class FacilityController extends Controller
{
    function search(Request $request){
        $search = $request->get('search');

        return Facility::with('region','province','city')->where('facility_name','like','%'.$search.'%')->orWhere('facility_cd','=',$search)->get();
    }

    function register(Request $request){
        $facility = $request->get('facility');
        $user_id = $request->get('user_id');
        $config = $request->get('config');
        $users = $request->get('users');
        $template = $request->get('template');
        
        extract($facility);
        
        $count = Facility::selectRaw('max(facility_cd) as facility_cd')->where('facility_cd','like','08%')->whereAddressRegion($region)->first();
        if($count){
            $count = $count->facility_cd;
        }else{
            $count = 1;
        }
        $count++;
        $facility_cd = $region.str_pad($count,3,'0',STR_PAD_LEFT);

        $f = new Facility;
        $f->facility_cd = $facility_cd;
        $f->facility_name = $facility_name;
        $f->type = $facility_type;
        $f->category = $category;
        $f->owner_name = $owner_name;
        $f->address_region = $region;
        $f->address_prov = $province;
        $f->address_citymun = $city;
        $f->address_bgy = $barangay;
        $f->address_zip = $address_zip;
        $f->tel_no = $tel_no;
        $f->mobile_no = $mobile_no;
        $f->fax_no = $fax_no;
        $f->email = $email;
        $f->contact_user_id = $contact_user_id;
        $f->contact_name = $contact_name;
        $f->designation = $designation;

        extract($config);

        $f->bsf_av = $bsf_av;
        $f->max_donor_age = $max_donor_age;
        $f->min_donor_age = $min_donor_age;
        $f->no_months_to_nxt_don = $no_months_to_nxt_don;
        $f->res_hrs = $res_hrs;
        $f->no_days_expire_warning = $no_days_expire_warning;
        $f->standard_bu_duration = $standard_bu_duration;
        $f->disable_flg = $disable_flg;

        $f->created_by = $user_id;
        $f->created_dt = date('Y-m-d H:i:s');

        return $f;
    }
}

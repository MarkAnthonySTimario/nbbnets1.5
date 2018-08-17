<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;
use App\Template;
use App\R3Config;
use App\TransfusionConfig;

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
        extract($config);
        
        // Facility Code
            $count = Facility::selectRaw('max(facility_cd) as facility_cd')->whereRaw('CHAR_LENGTH(facility_cd) = 5')->where('facility_cd','like',$region.'%')->first();
            if($count){
                $count = $count->facility_cd;
                if(strlen($count) == 5){
                    $count = substr($count,2,strlen($count));
                }
                $count = abs($count);
            }else{
                $count = 0;
            }
            $count++;
            $facility_cd = $region.str_pad($count,3,'0',STR_PAD_LEFT);
        // End Facility Code
        
        // Facility Details
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
            $f->fax = $fax;
            $f->email = $email;
            $f->contact_user_id = $contact_user_id;
            $f->contact_name = $contact_name;
            $f->designation = $designation;
            $f->bsf_av = $bsf_av;
            $f->max_donor_age = $max_donor_age;
            $f->min_donor_age = $min_donor_age;
            $f->no_months_to_nxt_don = $no_months_to_nxt_don;
            $f->res_hrs = $res_hrs;
            $f->no_days_expire_warning = $no_days_expire_warning;
            $f->standard_bu_duration = $standard_bu_duration;
            $f->disable_flg = $disable_flg;
            $f->nat = $nat;
            $f->antibody = $antibody;
            $f->zika = $zika;

            $f->created_by = $user_id;
            $f->created_dt = date('Y-m-d H:i:s');

            $f->save();
        // End Facility Details

        // Label Template
            $l = new Template;
            $l->facility_cd = $facility_cd;
            $l->template = $template;
            $l->save();
        // End Label Template
        
        // R3 Config
            $r3 = new R3Config;
            $r3->facility_cd = $facility_cd;
            $r3->access_flg = $access_flg;
            $r3->report_email = $report_email;
            $r3->admin_decide_flg = $admin_decide_flg;
            $r3->user_access_flg = $user_access_flg;
            $r3->user_request_flg = $user_request_flg;
            $r3->save();
        // End R3 Config

        // Transfusion Config
            $t = new TransfusionConfig;
            $t->facility_cd = $facility_cd;
            $t->enable_patient_ward_no = $enable_patient_ward_no;
            $t->enable_patient_room_no = $enable_patient_room_no;
            $t->enable_patient_bed_no = $enable_patient_bed_no;
            $t->save();
        // End Transfusion Config

        return $f;
    }

    function info(Request $request){
        $facility_cd = $request->get('facility_cd');

        $f = Facility::with('lead','region','province','city','barangay','r3config','transfusion_config')->whereFacilityCd($facility_cd)->first();

        return $f;
    }

    function appFacilityList(){
        return Facility::all()->pluck('facility_name','facility_cd');
    }
}

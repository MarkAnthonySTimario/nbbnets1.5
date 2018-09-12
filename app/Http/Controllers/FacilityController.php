<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;
use App\Template;
use App\R3Config;
use App\TransfusionConfig;
use App\User;

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
        // return $users;
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
            $f->lead_facility = isset($lead_facility) ? $lead_facility : null;
            $f->type = $facility_type;
            $f->category = $category;
            $f->address_no_st_blk = $address_no_st_blk;
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
            $f->standard_bu_duration = $standard_bu_duration ? 'Y' : 'N';
            $f->supports_platelet_apheresis = $supports_platelet_apheresis;
            $f->disable_flg = $disable_flg ? 'Y' : 'N';
            $f->nat = $nat;
            $f->antibody = $antibody;
            $f->zika = $zika ;

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
            $r3->access_flg = $access_flg ? 'Y' : 'N';
            $r3->report_email = $report_email;
            $r3->admin_decide_flg = $admin_decide_flg ? 'Y' : 'N';
            $r3->user_access_flg = $user_access_flg ? 'Y' : 'N';
            $r3->user_request_flg = $user_request_flg ? 'Y' : 'N';
            $r3->save();
        // End R3 Config

        // Transfusion Config
            $t = new TransfusionConfig;
            $t->facility_cd = $facility_cd;
            $t->enable_patient_ward_no = $enable_patient_ward_no ? 'Y' : 'N';
            $t->enable_patient_room_no = $enable_patient_room_no ? 'Y' : 'N';
            $t->enable_patient_bed_no = $enable_patient_bed_no ? 'Y' : 'N';
            $t->save();
        // End Transfusion Config

        // Users
            foreach($users as $user){
                $u = new User;
                $u->facility_cd = $facility_cd;
                $u->user_id = $facility_cd.'_'.$user['user_id'];
                $u->ulevel = $user['ulevel'];
                $u->password = md5($facility_cd);
                $u->user_lname = $user['lname'];
                $u->user_mname = $user['mname'];
                $u->user_fname = $user['fname'];
                $u->disable_flag = 'N';
                $u->save();
            }
        // End Users

        return $f;
    }

    function update(Request $request){
        $facility = $request->get('facility');
        $user_id = $request->get('user_id');
        $config = $request->get('config');
        $template = $request->get('template');
        
        extract($facility);
        extract($config);
        
        // Facility Details
            $f = Facility::find($facility_cd);
            $f->facility_name = $facility_name;
            $f->lead_facility = isset($lead_facility) ? $lead_facility : null;
            $f->type = $facility_type;
            $f->category = $category;
            $f->address_no_st_blk = $address_no_st_blk;
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
            $f->standard_bu_duration = $standard_bu_duration ? 'Y' : 'N';
            $f->supports_platelet_apheresis = $supports_platelet_apheresis;
            $f->disable_flg = $disable_flg ? 'Y' : 'N';
            $f->nat = $nat;
            $f->antibody = $antibody;
            $f->zika = $zika ;

            $f->updated_by = $user_id;
            $f->updated_dt = date('Y-m-d H:i:s');

            $f->save();
            
        // End Facility Details

        // Label Template
            $l = Template::whereFacilityCd($facility_cd)->first();
            // $l->facility_cd = $facility_cd;
            if(!$l){
                $l = new Template;
                $l->facility_cd = $facility_cd;
            }
            $l->template = $template;
            $l->save();
        // End Label Template
        
        // R3 Config
            $r3 = R3Config::whereFacilityCd($facility_cd)->first();
            if(!$r3){
                $r3 = new R3Config;
            }
            $r3->facility_cd = $facility_cd;
            $r3->access_flg = $access_flg ? 'Y' : 'N';
            $r3->report_email = $report_email;
            $r3->admin_decide_flg = $admin_decide_flg ? 'Y' : 'N';
            $r3->user_access_flg = $user_access_flg ? 'Y' : 'N';
            $r3->user_request_flg = $user_request_flg ? 'Y' : 'N';
            $r3->save();
        // End R3 Config

        // Transfusion Config
            $t = TransfusionConfig::whereFacilityCd($facility_cd)->first();
            if(!$t){
                $t = new TransfusionConfig;
            }
            $t->facility_cd = $facility_cd;
            $t->enable_patient_ward_no = $enable_patient_ward_no ? 'Y' : 'N';
            $t->enable_patient_room_no = $enable_patient_room_no ? 'Y' : 'N';
            $t->enable_patient_bed_no = $enable_patient_bed_no ? 'Y' : 'N';
            $t->save();
        // End Transfusion Config

        return $f;
    }

    function info(Request $request){
        $facility_cd = $request->get('facility_cd');

        $f = Facility::with('lead','region','province','city','barangay','r3config','transfusion_config','users','users.level')
                ->whereFacilityCd($facility_cd)
                ->first();

        return $f;
    }

    function appFacilityList(){
        return Facility::all()->pluck('facility_name','facility_cd');
    }

    function users($facility_cd){
        return User::select('user_id','user_fname','user_lname')->with('level')->whereFacilityCd($facility_cd)->get();
    }
}

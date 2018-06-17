<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\DonorLogController;
use App\Donor;

class DonorController extends Controller
{
    function search(Request $request){
        
        $fname = $request->get('fname');
        $mname = $request->get('mname');
        $lname = $request->get('lname');

        $search = Donor::with('region','province','city','barangay')
            ->exclude(['donor_photo'])
            ->where(function($s) use ($fname,$mname,$lname){

                if($fname){
                    $s->where('fname','like','%'.$fname.'%');
                }
    
                if($mname){
                    $s->where('mname','like','%'.$mname.'%');
                }
    
                if($lname){
                    $s->where('lname','like','%'.$lname.'%');
                }
            })->limit(20)->get();

        foreach($search as $i => $donor){
            $search[$i] = $this->replaceNye($donor);
        }

            
        return $search;
    }

    private function replaceNye($donor){
        if($donor->region){
            $donor->region->regname = str_replace("??","Ñ",$donor->region->regname);
            $donor->region->regname = str_replace("Â","Ñ",$donor->region->regname);
        }

        if($donor->province){
            $donor->province->provname = str_replace("??","Ñ",$donor->province->provname);
            $donor->province->provname = str_replace("Â","Ñ",$donor->province->provname);
        }

        if($donor->city){
            $donor->city->cityname = str_replace("??","Ñ",$donor->city->cityname);
            $donor->city->cityname = str_replace("Â","Ñ",$donor->city->cityname);
        }

        if($donor->barangay){
            $donor->barangay->bgyname = str_replace("??","Ñ",$donor->barangay->bgyname);
            $donor->barangay->bgyname = str_replace("Â","Ñ",$donor->barangay->bgyname);
        }
        
        return $donor;
    }

    function info(Request $request,$seqno){
        $donor = Donor::with('donations','donations.facility','nation','region','province','city','barangay','officeregion','officeprovince','officecity','officebarangay')->whereSeqno($seqno)->firstOrFail();
        if($donor->donor_photo){
            $donor->donor_photo = true;
        }
        return $donor;
    }

    function photo(Request $request,$seqno){
        $donor_photo = Donor::select('donor_photo')->whereSeqno($seqno)->firstOrFail()->donor_photo;
        if(substr_count($donor_photo,'data:image/jpeg;base64,') > 0){
            $donor_photo = str_replace('data:image/jpeg;base64,','',$donor_photo);
        }else{
            $donor_photo = base64_encode($donor_photo);
        }
        return $donor_photo;
    }

    function create(Request $request){
        $fields = [
            'donor_photo','donor_id', 'name_suffix', 'lname', 'fname', 'mname', 'bdate', 'gender', 'civil_stat', 
            'tel_no', 'mobile_no', 'email', 'nationality'];
        $donor = new Donor;
        foreach($fields as $field){
            if($request->has($field)){
                $donor->$field = $request->get($field);
            }
        }
        // $donor->donor_photo = str_replace('data:image/jpeg;base64,','',$donor->donor_photo);
        // $donor->donor_photo = base64_decode($donor->donor_photo);
        // $donor->donor_photo = utf8_encode($donor->donor_photo);
        $home = $request->get('home');
        $donor->home_no_st_blk = $home['no_st_blk'];
        $donor->home_zip = $home['zip'];
        $donor->home_region = $home['region'];
        $donor->home_prov = $home['province'];
        $donor->home_citymun = $home['city'];
        $donor->home_brgy = $home['barangay'];
        $office = $request->get('office');
        $donor->office_no_st_blk = $office['no_st_blk'];
        $donor->office_zip = $office['zip'];
        $donor->office_region = $office['region'];
        $donor->office_prov = $office['province'];
        $donor->office_citymun = $office['city'];
        $donor->office_brgy = $office['barangay'];
        $facility_cd = $request->get('facility_cd');
        $donor->seqno = Donor::generateSeqno($facility_cd);
        $donor->facility_cd = $facility_cd;
        $donor->created_by = $request->get('user_id');
        $donor->created_dt = date('Y-m-d H:i:s');
        $donor->save();

        // We dont need to capture original data of donor, since old_value is also captured upon update
        // DonorLogController::saveFieldsNewDonor($request,$donor->seqno,$request->get('user_id'));
        return $donor;
    }

    function update(Request $request){
        $fields = [
            'donor_photo','donor_id', 'name_suffix', 'lname', 'fname', 'mname', 'bdate', 'gender', 'civil_stat', 
            'tel_no', 'mobile_no', 'email', 'nationality'];
        $donor = Donor::whereSeqno($request->get('seqno'))->firstOrFail();
        
        DonorLogController::saveFieldsExistingDonor($request,$donor,$request->get('user_id'));
        
        foreach($fields as $field){
            if($request->has($field)){
                $donor->$field = $request->get($field);
            }
        }
        
        // $donor->donor_photo = str_replace('data:image/jpeg;base64,','',$donor->donor_photo);
        // $donor->donor_photo = base64_decode($donor->donor_photo);
        // $donor->donor_photo = utf8_encode($donor->donor_photo);
        $home = $request->get('home');
        $donor->home_no_st_blk = $home['no_st_blk'];
        $donor->home_zip = $home['zip'];
        $donor->home_region = $home['region'];
        $donor->home_prov = $home['province'];
        $donor->home_citymun = $home['city'];
        $donor->home_brgy = $home['barangay'];
        $office = $request->get('office');
        $donor->office_no_st_blk = $office['no_st_blk'];
        $donor->office_zip = $office['zip'];
        $donor->office_region = $office['region'];
        $donor->office_prov = $office['province'];
        $donor->office_citymun = $office['city'];
        $donor->office_brgy = $office['barangay'];
        $donor->updated_by = $request->get('user_id');
        $donor->updated_dt = date('Y-m-d H:i:s');
        $donor->save();

        
        
        return $donor;
    }

    
}

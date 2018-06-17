<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DonorLog;

class DonorLogController extends Controller
{
    static function saveFieldsNewDonor(Request $request,$seqno,$user){
        $data = $request->all();
        $home = $data['home'];
        unset($data['home']);
        $data['home_no_st_blk'] = $home['no_st_blk'];
        $data['home_region'] = $home['region'];
        $data['home_prov'] = $home['province'];
        $data['home_citymun'] = $home['city'];
        $data['home_brgy'] = $home['barangay'];
        $data['home_zip'] = $home['zip'];
        $office = $data['office'];
        unset($data['office']);
        $data['office_no_st_blk'] = $office['no_st_blk'];
        $data['office_region'] = $office['region'];
        $data['office_prov'] = $office['province'];
        $data['office_citymun'] = $office['city'];
        $data['office_brgy'] = $office['barangay'];
        $data['office_zip'] = $office['zip'];

        foreach($data as $key => $field){
            if($key != 'donor_photo' && strlen($field) > 0){
                $log = new DonorLog;
                $log->seqno = $seqno;
                $log->created_dt = date('Y-m-d H:i:s');
                $log->field_name = $key;
                $log->new_value = $field;
                $log->created_by = $user;
                $log->save();
            }
        }
    }

    static function saveFieldsExistingDonor(Request $request,$donor,$user){
        $data = $request->all();
        $seqno = $donor->seqno;

        $home = $data['home'];
        unset($data['home']);
        $data['home_no_st_blk'] = $home['no_st_blk'];
        $data['home_region'] = $home['region'];
        $data['home_prov'] = $home['province'];
        $data['home_citymun'] = $home['city'];
        $data['home_brgy'] = $home['barangay'];
        $data['home_zip'] = $home['zip'];
        $office = $data['office'];
        unset($data['office']);
        $data['office_no_st_blk'] = $office['no_st_blk'];
        $data['office_region'] = $office['region'];
        $data['office_prov'] = $office['province'];
        $data['office_citymun'] = $office['city'];
        $data['office_brgy'] = $office['barangay'];
        $data['office_zip'] = $office['zip'];

        foreach($data as $key =>$field){
            if($key != 'donor_photo' && strlen($field) > 0){
                if($field != $donor[$key]){
                    $log = new DonorLog;
                    $log->seqno = $seqno;
                    $log->created_dt = date('Y-m-d H:i:s');
                    $log->field_name = $key;
                    $log->old_value = $donor[$key];
                    $log->new_value = $field;
                    $log->created_by = $user;
                    $log->save();
                }
            }
        }
    }
}

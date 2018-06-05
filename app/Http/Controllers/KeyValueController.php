<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Component;
use App\BloodType;
use App\CodeValue;
use App\Nation;
use App\Exam;
use App\User;
use DB;

class KeyValueController extends Controller
{
    function keyvalues(){
        return [
            'components' => $this->components(),
            'bloodtypes' => $this->bloodtypes(),
            'civilstatus' => $this->civilstatus(),
            'bloodbags' => $this->bloodbags()
        ];
    }

    function components(){
        return Component::whereDisableFlg('N')->pluck('comp_name','component_cd');
    }

    function bloodtypes(){
        return BloodType::pluck('blood_type');
    }

    function civilstatus(){
        // return CodeValue::whereCode('CIVIL_STAT')->whereDisableFlg('N')->pluck('code_val','codedtl_cd');
        return [
            'S' => 'Single', 'M' => 'Married', 'W' => 'Widowed', 'SP' => 'Separated'
        ];
    }

    function bloodbags(){
        return CodeValue::whereCode('BLOOD_BAG')->whereDisableFlg('N')->pluck('code_val','codedtl_cd');
    }

    function contact_search(Request $request){
        $search = $request->get('search');
        return User::with('facility')->whereDisableFlag('N')
                ->where(function($query) use ($search){
                    $query->where(DB::raw('concat(user_fname," ",user_lname)') , 'LIKE' , '%'.$search.'%')
                        ->orWhere('user_id','=',$search);
                })->get();
    }

    function contact_info(Request $request,$user_id){
        return User::with('facility')->whereDisableFlag('N')->whereUserId($user_id)->first();
    }

    function nations(){
        return Nation::select(\DB::raw("CONCAT_WS(', ',country,nationality) as 'nation'"),'countrycode')
        ->get();
    }

    function donationtypes(){
        return CodeValue::whereCode('DONATION_TYPE')->whereDisableFlg('N')->pluck('code_val','codedtl_cd');
    }

    function donorstatuses(){
        return CodeValue::whereCode('DONOR_STAT')->whereDisableFlg('N')->pluck('code_val','codedtl_cd');
    }

    function collectionmethods(){
        return CodeValue::whereCode('COLLECTION_METHOD')->whereDisableFlg('N')->pluck('code_val','codedtl_cd');
    }

    function collectionstatuses(){
        return CodeValue::whereCode('DONATION_STAT')->whereDisableFlg('N')->pluck('code_val','codedtl_cd');
    }

    function discardreasons(){
        return CodeValue::whereCode('DISCARD')->whereDisableFlg('N')->pluck('code_val','codedtl_cd');
    }

    function exams(){
        return Exam::whereDisableFlg('N')->pluck('exam_name','exam_cd');
    }
}

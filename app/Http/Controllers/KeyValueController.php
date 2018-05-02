<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Component;
use App\BloodType;
use App\CodeValue;
use App\Nation;
use App\Exam;

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

    function exams(){
        return Exam::whereDisableFlg('N')->pluck('exam_name','exam_cd');
    }
}

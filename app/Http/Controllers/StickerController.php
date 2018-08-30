<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sticker;

class StickerController extends Controller
{

    function list($facility_cd){
        return Sticker::whereFacilityCd($facility_cd)->get();
    }

    function register(Request $request){
        $facility_cd = $request->get('facility_cd');
        $series_start = $request->get('series_start');
        $series_end = $request->get('series_end');


        $s = new Sticker;
        $s->facility_cd = $facility_cd;
        $s->year = '2018';
        $s->start = $series_start;
        $s->end = $series_end;
        $s->save();
        return ["status" => true];
    }

    function check($facility_cd,$donation_id){
        $year = substr($donation_id,5,4);
        $deliveries = Sticker::select('start','end')->whereFacilityCd($facility_cd)->where('year','=',$year)->get();
        
        $attempts = 0;
        foreach($deliveries as $delivery){
            if(!$this->checkBatch($delivery,$donation_id)){
                $attempts++;
            }
        }

        if($attempts < $deliveries->count()){
            return ['status' => true];
        }

        return ['status' => false];
    }

    function checkBatch($delivery,$donation_id){
        
        $start = $delivery->start;
        $end = $delivery->end;
        $start = abs(substr($start,9,7));
        $end = abs(substr($end,9,7));
        $suspect = abs(substr($donation_id,9,7));
        // dd($suspect);

        // echo "<pre>";
        // print_r(get_defined_vars());
        // echo "</pre><br/>-------------------";
        
        if($suspect >= $start && $suspect <= $end){
            return true;
        }

        return false;
    }
}

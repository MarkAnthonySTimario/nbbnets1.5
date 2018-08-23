<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Agency;
use App\Don;

class AgencyUpdateController extends Controller
{
    function agencies(){
        return Agency::with('province','city','barangay')
        // ->whereNull('cityproper')
        ->whereFacilityCd(11001)
        ->whereNull('distance')
        ->orderBy('adg_prov')
        ->limit(10)->get();
    }

    function remote(Request $request){
        $agency_cd = $request->get('agency_cd');
        $noncityproper = $request->get('noncityproper');
        $agency= Agency::find($agency_cd);
        $agency->cityproper = !$noncityproper;
        $agency->save();
    }

    function distance(Request $request){
        $agency_cd = $request->get('agency_cd');
        $distance = $request->get('distance');
        $agency= Agency::find($agency_cd);
        $agency->distance = $distance;
        $agency->save();
    }

    function distance2(Request $request){
        // $from = "Philippine Blood Center, Quezon Avenue, Diliman, Quezon City, Metro Manila";
        $to = $request->get('address');
        $agency_cd = $request->get('agency_cd');
        // $from = urlencode($from);
        $to = urlencode($to);
        // $data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
        $data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=Davao+Blood+Center&destinations=".$to);
        $data = json_decode($data);
        $time = 0;
        $distance = 0;
        foreach($data->rows[0]->elements as $road) {
            $time += $road->duration->value;
            $distance += $road->distance->value;
        }
        // return ['data' => $distance];
        $a = Agency::find($agency_cd);
        $a->distance = $distance/1000;
        $a->save();
        // echo "To: ".$data->destination_addresses[0];
        // echo "<br/>";
        // echo "From: ".$data->origin_addresses[0];
        // echo "<br/>";
        // echo "Time: ".$time." seconds";
        // echo "<br/>";
        // echo "Distance: ".$distance." meters";
    }

    function report1(Request $request){
        $agencies = Agency::with('barangay','city','province','region','donors')
            ->whereFacilityCd($request->facility_cd)
            // ->where('created_dt','like','2018-%')
            // ->groupBy('agency_cd')
            ->orderBy('distance','desc')
            // ->limit(3,1)
            // ->skip(0)
            // ->take(3)
            ->get();

        // foreach( $agencies as $i => $agency ){
        //     $agencies[$i]->fdonors = Don::whereAgencyCd($agency->agency_cd)->count();
        // }

        return $agencies;
    }
}

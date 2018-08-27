<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;
use App\Blood;
use App\Donation;

class BloodBankNetworkingController extends Controller
{
    function search(Request $request){
        $from = $request->get('from');
        $details = $request->get('details');

        
        // $facilities = 
    }

    function getFacilitiesWithStocks($facility_name){
        // $facility_cds = Blood::select('location')
        // ->whereCompStat('AVA')
        // ->groupBy('location')
        // ->pluck('location');
        $facility_cds = Donation::select('facility_cd')
            ->groupBy('facility_cd')
            ->pluck('facility_cd');

        $facilities = Facility::select('facility_cd','facility_name')
        ->whereIn('facility_cd',$facility_cds)->get();

        foreach($facilities as $i => $facility){
            $bloods = Blood::select('component_cd','blood_type')
                ->selectRaw('count(*)')
                ->whereLocation($facility->facility_cd)
                ->whereCompStat('AVA')
                ->groupBy('component_cd','blood_type')
                ->get();
            $facilities[$i]->bloods = $bloods;
            // $facilities[$i]->distance = $this->getDistance($facility_name,$facility->facility_name);
        }
        return $facilities;
    }

    function getDistance(Request $request){
        $from = $request->get('from');
        $to = $request->get('to');
        $from = urlencode($from);
        $to = urlencode($to);
        
        $data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$from."&destinations=".$to);
        $data = json_decode($data);
        $time = 0;
        $distance = 0;
        foreach($data->rows[0]->elements as $road) {
            if(property_exists($road,'duration')){
                $time += $road->duration->value;
                $distance += $road->distance->value;
            }
        }
        
        $time = round($time/60/60,2);
        if($time > 1){
            $time .= ' Hours';
        }else{
            $time .= ' Minutes';
        }

        $distance = round($distance/1000,2);
        
        return [
            'time' => $time, 'distance' => $distance
        ];

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;
use App\Blood;
use App\Donation;
use App\EmergencyPool;
use App\NetworkIntent;

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
            $facilities[$i]->bloods = $this->getBloods($facility);
            // $facilities[$i]->distance = $this->getDistance($facility_name,$facility->facility_name);
        }
        return $facilities;
    }

    function getDistance(Request $request){
        $from = $request->get('from');
        $to = $request->get('to');
       
        return $this->distance($from,$to);

    }

    function distance($from,$to){
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

    function facility(Request $request){
        $facility_cd = $request->get('facility_cd');
        $origin = $request->get('origin');

        $facility = Facility::find($facility_cd);
        $facility->distance = $this->distance($origin['facility_name'],$facility->facility_name);
       
        $facility->bloods = $this->getBloods($facility);
        return $facility;
    }

    function getBloods($facility){
        $bloods = Blood::select('component_cd','blood_type')
        ->selectRaw('count(*) as quantity')
        ->whereLocation($facility->facility_cd)
        ->whereCompStat('AVA')
        ->where('expiration_dt','>=',date('Y-m-d'))
        ->groupBy('component_cd','blood_type')
        ->get();

        $emergency = EmergencyPool::select('blood_type','component_cd','pool')->whereFacilityCd($facility->facility_cd)->get();
        $ep = [];
        foreach($emergency as $e){
            $ep[$e->blood_type.$e->component_cd] = $e->pool;
        }

        foreach($bloods as $i => $blood){
            if($facility->bsf_av){
                $bloods[$i]->quantity = round($blood->quantity * ($facility->bsf_av/100));
            }else{
                $bloods[$i]->quantity = $blood->quantity;
            }
            if(array_key_exists($blood->blood_type.$blood->component_cd,$ep) !== false){
                $emergencyPool = $ep[$blood->blood_type.$blood->component_cd];
                $bloods[$i]->quantity -= $emergencyPool;
            }
            if($bloods[$i]->quantity < 0){
                $bloods[$i]->quantity = 0;
            }
        }


        return $bloods;
    }

    function addIntent(Request $request){
        $from = $request->get('from');
        $to = $request->get('to');
        $details = json_encode($request->get('details'));
        $by = $request->get('by');
        // return json_encode($details);

        $intent = new NetworkIntent;
        $intent->from = $from;
        $intent->to = $to;
        $intent->details = $details;
        $intent->by = $by;
        $intent->save();
    }

    function facilities(){
        return Facility::select('facility_name','facility_cd')->get();
    }

    function getIntents(Request $request){
        $from = $request->get('from');
        $to = $request->get('to');
        return NetworkIntent::whereFrom($from)->whereTo($to)->select('id','created_at','by','details')->get();
    }
}

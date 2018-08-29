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
        $intent->serve = false;
        $intent->save();
    }

    function facilities(){
        return Facility::select('facility_name','facility_cd')->get();
    }

    function getIntents(Request $request){
        $from = $request->get('from');
        $to = $request->get('to');
        return NetworkIntent::whereFrom($from)
        ->whereTo($to)
        ->whereServe(0)
        ->select('id','created_at','by','details')
        ->get();
    }

    function intentAvailable(Request $request){
        $facility_cd = $request->get('facility_cd');
        $facility = Facility::find($facility_cd);

        return $this->getBloods($facility);
        // $details = $request->get('details');

        // $emergency = EmergencyPool::select('blood_type','component_cd','pool')->whereFacilityCd($facility->facility_cd)->get();
        // $ep = [];
        // foreach($emergency as $e){
        //     $ep[$e->blood_type.$e->component_cd] = $e->pool;
        // }

        // foreach($details as $i => $d){
        //     $ava = Blood::selectRaw('count(*) as quantity')
        //     ->whereLocation($facility_cd)
        //     ->whereCompStat('AVA')
        //     ->where('expiration_dt','>=',date('Y-m-d'))
        //     ->whereComponentCd($d['component_cd'])
        //     ->whereBloodType($d['blood_type'])
        //     ->first();
        //     $details[$i]['available'] = $ava->quantity;

        //     if($facility->bsf_av){
        //         $details[$i]['available'] = round($details[$i]['available'] * ($facility->bsf_av/100));
        //     }
        //     if(array_key_exists($d['blood_type'].$d['component_cd'],$ep) !== false){
        //         $emergencyPool = $ep[$d['blood_type'].$d['component_cd']];
        //         $details[$i]['available'] -= $emergencyPool;
        //     }
        //     if($details[$i]['available'] < 0){
        //         $details[$i]['available'] = 0;
        //     }
        // }

        // return $details;
    }

    function serveIntent(Request $request){
        $id = $request->get('id');
        $reserved_by = $request->get('reserved_by');
        $details = $request->get('details');
        $facility_cd = $request->get('facility_cd');

        $intent = NetworkIntent::find($id);
        $intent->details = $details;
        $intent->reserved_by = $reserved_by;
        $intent->reserved_dt = date('Y-m-d h:i:s');
        $intent->serve = true;
        $intent->request_no = NetworkIntent::generateNo($facility_cd);
        $intent->save();

        return $intent;
    }

    function getServeIntent($facility_cd){
        return NetworkIntent::whereTo($facility_cd)
                ->with('facilityFrom')->get();
    }

    function deletServeIntent($id){
        $i = NetworkIntent::find($id);
        $i->delete();
    }

    function lookUpUnits(Request $r){
        $facility_cd = $r->get('facility_cd');
        $blood_type = $r->get('blood_type');
        $component_cd = $r->get('component_cd');

        return Blood::select('donation_id','expiration_dt')
        ->whereBloodType($blood_type)
        ->whereComponentCd($component_cd)
        ->whereLocation($facility_cd)
        ->whereCompStat('AVA')
        ->where('expiration_dt','>=',date('Y-m-d'))
        ->get();
    }
}

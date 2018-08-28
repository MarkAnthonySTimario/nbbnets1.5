<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmergencyPool;

class EmergencyPoolController extends Controller
{
    function get($facility_cd){
        return EmergencyPool::whereFacilityCd($facility_cd)->select('blood_type','component_cd','pool')->get();
    }

    function create(Request $request) {

        $facility_cd = $request->get('facility_cd');
        $blood_type = $request->get('blood_type');
        $component_cd = $request->get('component_cd');  
        $value =  $request->get('pool');
        
        $count = EmergencyPool::whereFacilityCd($facility_cd)
            ->where('blood_type','=',$blood_type)
            ->where('component_cd', '=', $component_cd)
            ->count();

        if($count != '0') {
            $record = EmergencyPool::whereFacilityCd($facility_cd)
            ->where('blood_type','=',$blood_type)
            ->where('component_cd', '=', $component_cd)
            ->first();
            
            $e = EmergencyPool::find($record->id)->id;
            $ev = EmergencyPool::find($e);
            $ev->pool = $value;
            $ev->updated_at = date('Y-m-d H:i:s');  
            $ev->save();
        }
        else {
            $e = new EmergencyPool();
            $e->facility_cd = $request->get('facility_cd');
            $e->blood_type = $request->get('blood_type');
            $e->component_cd = $request->get('component_cd');
            $e->pool = $request->get('pool');
            $e->created_at = date('Y-m-d H:i:s');
            $e->updated_at = date('Y-m-d H:i:s');       
            $e->save();
        }

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;

class FacilityController extends Controller
{
    function search(Request $request){
        $search = $request->get('search');

        return Facility::with('region','province','city')->where('facility_name','like','%'.$search.'%')->orWhere('facility_cd','=',$search)->get();
    }
}

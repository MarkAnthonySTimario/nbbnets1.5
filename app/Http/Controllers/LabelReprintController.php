<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\Label;

class LabelReprintController extends Controller
{
    function units($donation_id){
        return Donation::with('type','test','units','units.component')->whereDonationId($donation_id)->first();
    }

    function reprintFired(Request $request){
        $unit = $request->get('unit');
        $reason = $request->get('reason');
        
        $label = Label::whereDonationId($unit->donation_id)
                    ->whereComponentCd($unit->component_cd)
                    ->first();

        if($label){
            $label->reprint_count = $label->reprint_count++;
            $label->reason = $reason;
        }
    }
}

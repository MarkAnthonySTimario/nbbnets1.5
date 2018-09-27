<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Template;
use App\Facility;
use App\Blood;
use App\Donation;
use App\NetworkIntent;
use App\User;
use App\Component;
use App\MBD;

use Milon\Barcode\Facades\DNS1DFacade;
use Response;
use Storage;

class TemplateController extends Controller
{
    function lists(){
        return Template::with('facility')->get();
    }

    function save(Request $request){
        $facility = $request->get('facility');
        $template = $request->get('template');

        $existing = Template::whereFacilityCd($facility['facility_cd'])->first();

        if($existing){
            $existing->facility_cd = $facility['facility_cd'];
            $existing->template = $template;
            $existing->save();
            
        }else{
            $t = new Template();
            $t->facility_cd = $facility['facility_cd'];
            $t->template = $template;
            $t->save();
        }

    }

    function barcode($donation_id){
        $barcode = DNS1DFacade::getBarcodePNG($donation_id, "C128",2,30);
        $barcode = base64_decode($barcode);
        $response = Response::make($barcode);
        $response->header('Content-Type','image/png');
        return $response;
    }

    function preview(Request $request){
        $facility_cd = $request->get('facility_cd');
        $donation_id = $request->get('donation_id');
        $component_cd = $request->get('component_cd');

        $label = $this->prepareTemplate($facility_cd,$donation_id,$component_cd);
        return view('layout.label')->withContent($label);
    }

    function facilitypreview(Request $request){
        $facility_cd = $request->get('facility_cd');
        $unit = Blood::whereLocation($facility_cd)->whereNotIn('comp_stat',['EXP','DIS','ISS'])->first();
        $donation_id = $unit->donation_id;
        $component_cd = $unit->component_cd;
        $label = $this->prepareTemplate($facility_cd,$donation_id,$component_cd);
        return view('layout.label')->withContent($label);
    }
    
    function getTemplate($facility_cd){
        $template = Template::whereFacilityCd($facility_cd)->first();

        if($template){
            return $template->template;
        }
        // return asset('storage/label-template.html');
        $file = base_path('public').'/label-template.html';
        // Open the file to get existing content
        return file_get_contents($file);
        // return Storage::get('label-template.html');
    }

    private function prepareTemplate($facility_cd,$donation_id,$component_cd){
        
        $facility = Facility::whereFacilityCd($facility_cd)->firstOrFail();
        $unit = Blood::with('component','donation_min.mbd_min','donation.additionaltest','aliqoute_donation')
                    // ->whereLocation($facility_cd)
                    ->whereDonationId($donation_id)
                    ->whereComponentCd($component_cd)
                    ->whereNotIn('comp_stat',['EXP','DIS','ISS'])
                    ->first();
        if(!$unit){
            return '<div style="text-align:center;font-family:calibri;">Opps! Sorry, Blood Unit no longer available!</div>';
        }
        $bt = explode(' ',$unit->blood_type);
        if($unit->source_donation_id){
            $collection_dt = $unit->aliqoute_donation->sched_id == 'Walk-in' ? $unit->aliqoute_donation->created_dt : $unit->aliqoute_donation->mbd_min->donation_dt;
            $collection_dt = date('M d, Y',strtotime($collection_dt));
        }else{
            $collection_dt = $unit->donation_min->sched_id == 'Walk-in' ? $unit->donation_min->created_dt : $unit->donation_min->mbd_min->donation_dt;
            $collection_dt = date('M d, Y',strtotime($collection_dt));
        }
        $template = $this->getTemplate($facility_cd);
        
        $template = str_replace('{{FACILITY_NAME}}',$facility->facility_name,$template);
        $template = str_replace('{{BARCODE}}','<div style="font-size:14px;font-family:arial;background:#fff;width:98%;height:50px;text-align:center;vertical-align:middle;padding:2px;"><img src="barcode/'.$donation_id.'" width="95%" height="30" /><center><b>'.$donation_id.'</b></center></div>',$template);
        $template = str_replace('{{ABO}}',$bt[0],$template);
        $template = str_replace('{{RH}}',$bt[1],$template);
        $template = str_replace('{{COMPONENT}}',$unit->component->comp_name,$template);
        $template = str_replace('{{VOLUME}}',$unit->component_vol,$template);
        $template = str_replace('{{COLLECTION_DATE}}',$collection_dt,$template);
        $template = str_replace('{{EXPIRATION_DATE}}',date('M d, Y',strtotime($unit->expiration_dt)).' 23:59:00',$template);
        $template = str_replace('{{STORE}}','Store at '.$unit->component->min_storage.' to '.$unit->component->max_storage.' &deg;C',$template);
        if($unit->donation){
            if($unit->additionaltest){
                $template = str_replace('{{ANTIBODY}}','ANTIBODY SCREENING : NEGATIVE',$template);
                $template = str_replace('{{NAT}}','NUCLIEC ACID TESTING : NEGATIVE',$template);
                $template = str_replace('{{ZIKA}}','ZIKA : NEGATIVE',$template);
            }else{
                $template = str_replace('{{ANTIBODY}}','',$template);
                $template = str_replace('{{NAT}}','',$template);
                $template = str_replace('{{ZIKA}}','',$template);
            }
        }else{
            $template = str_replace('{{ANTIBODY}}','',$template);
            $template = str_replace('{{NAT}}','',$template);
            $template = str_replace('{{ZIKA}}','',$template);

        }

         return $template;
    }

    function checkunit(Request $request){
        $facility_cd = $request->get('facility_cd');
        $unit = Blood::whereLocation($facility_cd)->whereNotIn('comp_stat',['EXP','DIS','ISS'])->first();
        return $unit;
    }
    
    function createdummy(Request $request){
        $facility_cd = $request->get('facility_cd');
        
        $d = new Donation;
        $d->seqno = Donation::generateSeqno($facility_cd);
        $d->donation_id = 'NVBSP'.date('Y').'XX'.$facility_cd;
        $d->sched_id = 'Walk-in';
        $d->pre_registered = 'Y';
        $d->donation_type = 'V';
        $d->collection_method = 'WB';
        $d->donation_stat = 'A';
        $d->facility_cd = $facility_cd;
        $d->mh_pe_stat = 'A';
        $d->collection_stat = 'COL';
        $d->blood_bag = 'S';
        $d->created_by = $facility_cd;
        $d->created_dt = date('Y-m-d H:i:s');
        $d->save();

        $unit = new Blood;
        $unit->donation_id = 'NVBSP'.date('Y').'XX'.$facility_cd;
        $unit->component_cd = '10';
        $unit->blood_type = 'A pos';
        $unit->location = $facility_cd;
        $unit->expiration_dt = date('Y-m-d').' 23:59:00';
        $unit->component_vol = '500';
        $unit->comp_stat = 'FBL';
        $unit->created_by = $facility_cd;
        $unit->created_dt = date('Y-m-d H:i:s');
        $unit->save();
        return $unit;
    }

    function saveFacilityTemplate(Request $request){
        $facility_cd = $request->get('facility_cd');
        $template = $request->get('template');

        $t = Template::firstOrNew(['facility_cd' => $facility_cd]);
        $t->template = $template;
        $t->save();
    }

    function getReleaseForm($facility_cd = null){
        $file = base_path('public').'/release-form-template.html';
        return file_get_contents($file);
    }

    function release($intent_id){
        $intent = NetworkIntent::with('facilityFrom','facilityTo','units')->whereId($intent_id)->first();
        if($intent->released_dt == null){
            // return "Can't find record";
        }
        $template = $this->getReleaseForm();
        
        $releaseForm = $this->prepareRelease($template,$intent);
        return view('layout.label')->withContent($releaseForm);
    }

    function releasingForm($sched_id){
        $file = base_path('public').'/blood-releasing-form-template.html';
        $template = file_get_contents($file);
        
        $mbd = MBD::with('donations','donations.units')->find($sched_id);
        
        $donations = $this->prepareDonationsForReleasingForm($mbd);

        $mbd = str_replace("{{DONATIONS}}",$donations,$template);
        
        return view('layout.label')->withContent($mbd);
    }

    function prepareDonationsForReleasingForm($mbd){
        
        $components = Component::pluck('comp_name','component_cd');
        // foreach($components as $cd => $cn){
        //     if(){
                
        //     }
        // }
        $out = "";
        foreach($mbd->donations as $donation){
            foreach($donation->units as $unit){
                if($unit->comp_stat == 'FBT'){
                    $out .= "
                    <tr >
                        <td>".$unit->blood_type."</td>
                        <td>".$components[$unit->component_cd]."</td>
                        <td>".$unit->donation_id."</td>
                        <td>".$unit->collection_dt."</td>
                        <td>".$unit->expiration_dt."</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
                }
            }
        }
        
        return $out;
    }

    function prepareRelease($template,$intent){
        $components = Component::pluck('comp_name','component_cd');
        $to = $intent->facilityTo;
        $from = $intent->facilityFrom;
        
        $template = str_replace("{{FACILITY_NAME}}",$to->facility_name,$template);
        $template = str_replace("{{FACILITY_ADDRESS}}",$to->address_no_st_blk,$template);
        $template = str_replace("{{RELEASED_TO}}",$from->facility_name,$template);
        $template = str_replace("{{RELEASED_DT}}",$intent->released_dt,$template);
        $release = User::find($intent->released_by);
        if($release){
            $template = str_replace("{{RELEASED_BY}}",$release->user_fname.' '.$release->user_mname.' '.$release->user_lname,$template);
        }else{
            $template = str_replace("{{RELEASED_BY}}","",$template);
        }
        $verifier = User::find($intent->verified_by);
        $template = str_replace("{{VERIFIED_BY}}",$verifier->user_fname.' '.$verifier->user_mname.' '.$verifier->user_lname,$template);


        $units = [];
        // dd($intent->details);
        foreach($intent->units as $u){
            $b = Blood::select('expiration_dt')->whereDonationId($u->donation_id)->whereComponentCd($u->component_cd)->first();

            $units[] = "
                <tr>
                    <td style='border-bottom:1px solid #000; text-align:center;'>".$u->donation_id."</td>
                    <td style='border-bottom:1px solid #000; text-align:center;'>".$u->blood_type."</td>
                    <td style='border-bottom:1px solid #000; text-align:center;'>".$components[$u->component_cd]."</td>
                    <td style='border-bottom:1px solid #000; text-align:center;'>".$b->expiration_dt."</td>
                </tr>
            ";
        }

        $units = implode("",$units);
        $template = str_replace("{{UNITS}}",$units,$template);

        return $template;
    }
    
}

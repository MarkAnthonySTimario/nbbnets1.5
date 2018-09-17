<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Template;
use App\Facility;
use App\Blood;
use App\Donation;

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
                    ->whereLocation($facility_cd)
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
    
}

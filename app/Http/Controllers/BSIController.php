<?php

namespace App\Http\Controllers;
use App\Http\Controllers\BSI_p10;
use Illuminate\Http\Request;
use App\Donation;
use App\BSI;

class BSIController extends BSI_p10
{

    public $item_no = null;
    public $facility_cd = null;
    public $year = null;
    public $month = null;

    function fetch(Request $request){

        $this->item_no = str_replace('.','_',$request->get('question_no'));
        $this->facility_cd = $request->get('facility_cd');
        $this->user_id = $request->get('user_id');
        $this->year = $request->get('year');
        $this->month = $request->get('month');

        if(method_exists($this,'item_'.$this->item_no)){
            $method = 'item_'.$this->item_no;
            return $this->$method();
        }
    }

    function exist(Request $request) {
        $facility_cd = $request->get('facility_cd');
        return BSI::whereFacilityCd($facility_cd)->count();
    }

    function yesno(Request $request) {
        $facility_cd = $request->get('facility_cd');
        $question_no = $request->get('question_no');
        $question_no = str_replace(".", "_", $question_no);
        return BSI::whereFacilityCd($facility_cd)->select($question_no)->firstOrFail()->$question_no;
    }
      
    function create(Request $request){
        $facility_cd = $request->get('facility_cd');
        $user_id = $request->get('user_id');

        $record = BSI::whereFacilityCd($facility_cd)->first();


            $items = $request->get('items');
            $items = collect($items);
            
            $answers = [];
            for($i = 0; $i <= 40; $i++){
                $item = $items->get($i); 
                $answer = $item['value'];
                $answers[str_replace('.','_',$item['question_no'])] = $answer;
            }

            $answers['facility_cd'] = $facility_cd;
            $answers['created_by'] = $user_id;
            $answers['created_dt'] = date('Y-m-d H:i:s');
            // $answers['updated_by'] = $user_id;
            // $answers['updated_dt'] = date('Y-m-d H:i:s');            
           
            BSI::updateOrCreate(['facility_cd' => $facility_cd], $answers);       
        
    }   
   
}

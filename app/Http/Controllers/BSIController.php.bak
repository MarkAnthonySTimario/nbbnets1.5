<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BSI_p10;
use Illuminate\Http\Request;
use App\Donation;

class BSIController extends BSI_p10
{

    public $item_no = null;
    public $facility_cd = null;
    public $year = null;
    public $month = null;

    /*
    *   I-access mo to via api.php mo na parang
    *   Route::post('BSI/item','BSIController@fetch'); 
    *   Importante na kasama sa post request headers ang 
    *   ITEM NO / YEAR / MONTH / FACILITY CODE 
    */
    function fetch(Request $request){

        $this->item_no = str_replace('.','_',$request->get('item_no'));
        $this->facility_cd = $request->get('facility_cd');
        $this->year = $request->get('year');
        $this->month = $request->get('month');

        if(method_exists($this,'item_'.$this->item_no)){
            $method = 'item_'.$this->item_no;
            return $this->$method();
        }
    }

    

}

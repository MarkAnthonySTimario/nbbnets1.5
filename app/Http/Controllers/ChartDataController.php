<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blood;
use App\BloodType;
use App\Component;

class ChartDataController extends Controller
{
    function getStatusOfInventory($facility_cd){
        $blood_types = BloodType::orderBy('blood_type','asc')->pluck('blood_type');
        $components = Component::whereDisableFlg('N')
                        ->orderBy('component_cd','asc')
                        ->pluck('comp_name','component_cd');

        $available = Blood::select('blood_type','component_cd')
                    ->selectRaw('count(*) as cnt')
                    ->groupBy('blood_type','component_cd')
                    ->whereLocation($facility_cd)
                    ->whereCompStat('AVA')
                    ->where('expiration_dt','>',date('Y-m-d'))
                    ->get();
        
        $data = [];
        foreach($blood_types as $bt){
            $row = [$bt];
            foreach($components as $cd => $cn){
                $value = 0;
                foreach($available as $ava){
                    if($ava->blood_type == $bt && $ava->component_cd == $cd){
                        $value = $ava->cnt;
                    }
                }
                $row[] = $value;
            }
            $data[] = $row;
        }
        
        return $data;
    }

    function getReservedTable($facility_cd){
        return Blood::whereLocation($facility_cd)
                ->where('blood_type','!=','')
                ->whereCompStat('RES')
                ->where('expiration_dt','>',date('Y-m-d'))
                ->select('blood_type','component_cd')
                ->selectRaw('count(*) as cnt')
                ->groupBy('blood_type','component_cd')
                ->get();
    }

    function getReservedChart($facility_cd){
        $blood_types = BloodType::orderBy('blood_type','asc')->pluck('blood_type');
        $components = Component::whereDisableFlg('N')
                        ->orderBy('component_cd','asc')
                        ->pluck('comp_name','component_cd');

        $available = Blood::select('blood_type','component_cd')
                    ->selectRaw('count(*) as cnt')
                    ->groupBy('blood_type','component_cd')
                    ->whereLocation($facility_cd)
                    ->whereCompStat('AVA')
                    ->where('expiration_dt','>',date('Y-m-d'))
                    ->get();
        
        $data = [];
        foreach($blood_types as $bt){
            $row = [$bt];
            foreach($components as $cd => $cn){
                $value = 0;
                foreach($available as $ava){
                    if($ava->blood_type == $bt && $ava->component_cd == $cd){
                        $value = $ava->cnt;
                    }
                }
                $row[] = $value;
            }
            $data[] = $row;
        }
        
        return $data;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;
use App\User;
use App\UserLevel;

class AdminController extends Controller
{
    function facilitylistsimple(){
        return Facility::whereDisableFlg('N')->select('facility_cd','facility_name')->get();
    }

    function getUsers($facility_cd){
        return User::with('level')->select('ulevel','user_fname','user_mname','user_lname','disable_flag')->selectRaw("user_id as username")->whereFacilityCd($facility_cd)->get();
    }

    function getLevels(){
        return UserLevel::where('userlevelid','!=','-1')->get();
    }

    function addUser(Request $r){
        $facility_cd = $r->get('facility_cd');
        $user_id = $r->get('user_id');
        $ulevel = $r->get('ulevel');
        $user_fname = $r->get('user_fname');
        $user_mname = $r->get('user_mname');
        $user_lname = $r->get('user_lname');

        $u = new User;
        $u->user_id = $facility_cd.'_'.$user_id;
        $u->ulevel = $ulevel;
        $u->facility_cd = $facility_cd;
        $u->user_fname = $user_fname;
        $u->user_mname = $user_mname;
        $u->user_lname = $user_lname;
        $u->password = md5($facility_cd);
        $u->disable_flag = 'N';
        $u->save();
        return $u;
    }

    function updateUser(Request $r){
        $facility_cd = $r->get('facility_cd');
        $username = $r->get('username');
        $ulevel = $r->get('ulevel');
        $user_fname = $r->get('user_fname');
        $user_mname = $r->get('user_mname');
        $user_lname = $r->get('user_lname');

        $u = User::find($username);
        $u->ulevel = $ulevel;
        $u->user_fname = $user_fname;
        $u->user_mname = $user_mname;
        $u->user_lname = $user_lname;
        $u->save();
        return $u;
    }

    function checkUserId($user_id){
        return User::find($user_id);
    }

    function resetUserPassword(Request $r){
        $username = $r->get('username');
        $facility_cd = $r->get('facility_cd');
        
        $u = User::find($username);
        $u->password = md5($facility_cd);
        $u->save();
    }

    function disableUser(Request $r){
        $username = $r->get('username');
        $facility_cd = $r->get('facility_cd');
        
        $u = User::find($username);
        $u->disable_flag = 'Y';
        $u->save();
    }

    function activateUser(Request $r){
        $username = $r->get('username');
        $facility_cd = $r->get('facility_cd');
        
        $u = User::find($username);
        $u->disable_flag = 'N';
        $u->save();
    }
}

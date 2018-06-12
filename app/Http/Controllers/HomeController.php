<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Blood;

class HomeController extends Controller
{
    protected $client_id = 1;
    protected $client_secret = '';

    function __construct(){
        $this->client_id = env('API_CLIENT_ID');
        $this->client_secret = env('API_CLIENT_SECRET');
    }

    function login(Request $request){
        $user = User::with('facility','level')
        ->whereUserId($request->get('username'))
        ->wherePassword(md5($request->get('password')))
        ->first();

        $status = true;
        $error = null;

        if(!$user){
            $status = false;
            $error = "Login failed! Please check username/password.";
        }

        return [
            'status' => $status,
            'error' => $error,
            'user' => $user,
            'token' => $status ? $this->token() : null
        ];
    }

    function verify(Request $request){
        $current = $request->get('current_user_id');
        $username = $request->get('username');
        if($current == $username){
            return null;
        }
        return User::whereUserId($username)->wherePassword(md5($request->get('password')))->whereFacilityCd($request->get('facility_cd'))->first();
    }

    function token(){
        $http = new \GuzzleHttp\Client;
        
        $response = $http->post(url('/oauth/token'), [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    function stocks(Request $request){
        return Blood::whereLocation($request->get('facility_cd'))
                ->where('blood_type','!=','')
                ->whereCompStat('AVA')
                ->where('expiration_dt','>',date('Y-m-d'))
                ->select('blood_type','component_cd')
                ->selectRaw('count(*) as cnt')
                ->groupBy('blood_type','component_cd')
                ->get();
    }

    function changepassword(Request $request){
        // return md5($request->get('oldpassword'));
        $user = User::whereUserId($request->get('user_id'))
                ->wherePassword(md5($request->get('oldpassword')))
                ->first();
        // return $user;
        if(!$user){
            return 'wrong password';
        }
        $user->password = md5($request->get('newpassword'));

        $user->save();

        return $user;
        
    }

    function sound(Request $request){
        return env("NOTIFICAITON_SOUND_FILE",$request->root().'/chat.mp3');
    }

}


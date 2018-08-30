<?php

namespace App;

use Session;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'r_user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    static function current(){
        Session::get('user');
    }

    static function guest(){
        Session::has('user');
    }

    static function login($user){
        Session::put('user',$user);
    }

    static function logout(){
        Session::flush();
    }

    function facility(){
        return $this->belongsTo('App\Facility','facility_cd','facility_cd');
    }

    function level(){
        return $this->belongsTo('App\UserLevel','ulevel','userlevelid');
    }
}

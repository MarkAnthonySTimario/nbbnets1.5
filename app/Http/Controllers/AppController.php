<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Donor;
use App\Region;
use App\Province;
use App\City;
use App\Barangay;
use DB;

class AppController extends Controller
{
    function login(Request $request){
        $username = $request->get('username');
        $password = $request->get('password');
        $user = User::select('user_id','ulevel','user_fname','user_mname','user_lname','facility_cd')->whereUserId($username)->where('password','=',MD5($password))->first();
        return ['status' => $user ? 'ok' : 'fail', 'user' => $user];
    }

    function count($last){
        $count = Donor::whereHomeRegion($region)->where('seqno','<',$last)->selectRaw(
            "count(*) as `cnt`"
        )->first()->cnt;
        return ['status' => 'ok', 'data' => $count];
    }

    function checkupdates($rows,$last){
        $donors = Donor::select(
            'seqno',
            'donor_id',
            'fname',
            'mname',
            'lname',
            'gender',
            'bdate',
            'home_no_st_blk',
            'home_brgy',
            'home_prov',
            'home_citymun',
            'home_region',
            'donation_stat',
            'created_by',
            'created_dt'
        )->whereHomeRegion($region)->where('seqno','<',$last)->paginate($rows);//->count();
        return $donors;
    }

    function getUpdateCount($regcode,$last){
        if($regcode == '00'){
            $count = Donor::whereNull('home_region')->where('seqno','>',$last)->selectRaw("count(*) as `cnt`")->first()->cnt;
        }else{
            $count = Donor::whereHomeRegion($regcode)->where('created_dt','>=',$last)->selectRaw("count(*) as `cnt`")->first()->cnt;
        }
        $barangays = Barangay::whereRegcode($regcode)->selectRaw("count(*) as `cnt`")->first()->cnt;
        return ['status' => 'ok', 'data' => ['count' => $count, 'regcode' => $regcode, 'barangays' => $barangays]];
    }

    function getUpdate($regcode,$last){
        if($regcode == '00'){
            $donors = Donor::select(
            'seqno',
            'donor_id',
            'fname',
            'mname',
            'lname',
            'gender',
            'bdate',
            'home_no_st_blk',
            'home_brgy',
            'home_prov',
            'home_citymun',
            'home_region',
            'donation_stat',
            'created_by',
            'created_dt'
            )->whereNull('home_region')->where('seqno','>',$last)->orderBy('seqno','asc')->limit(10000)->get();//->count();
        }else{
            $donors = Donor::select(
            'seqno',
            'donor_id',
            'fname',
            'mname',
            'lname',
            'gender',
            'bdate',
            'home_no_st_blk',
            'home_brgy',
            'home_prov',
            'home_citymun',
            'home_region',
            'donation_stat',
            'created_by',
            'created_dt'
            )->whereHomeRegion($regcode)->where('created_dt','>=',$last)->orderBy('created_dt','asc')->get();//->count();
        }
        
        return ['status'=>'ok','data'=>$donors];
        // return ['status'=>'ok','data'=>[$regcode,$last]];
    }

    function regions(){
        $data = Region::select('regcode','regname')->get();
        return ['status' => 'ok', 'data' => $data];
    }

    function provinces(){
        $data = Province::select('regcode','provcode','provname')->get();
        return ['status' => 'ok', 'data' => $data];
    }

    function cities(){
        $data = City::select('regcode','provcode','citycode','cityname')->get();
        return ['status' => 'ok', 'data' => $data];
    }

    function barangays($regcode){
        $data = Barangay::select('regcode','provcode','citycode','bgycode','bgyname')->whereRegcode($regcode)->get();
        return ['status' => 'ok', 'data' => $data];
    }

    function photo($seqno){
        $donor = Donor::select('seqno','donor_photo')->whereSeqno($seqno)->first();
        if(!$donor){
            return ['status' => 'ok','data'=>null];
        }
        $photo = base64_encode($donor->donor_photo);
        return ['status' => 'ok', 'data' => $photo];
    }

    function news($max){
        if($max){
            return ['status' => 'ok', 'data' => []];
        }
        $data = [
                [
                    'id' => 1, 'title' => 'OFFICE OF THE SECRETARY. OCTOBER 25, 2015 - A.O. 2015 - 0045',
                    'content' => 'SUBJECT: New maximum allowable service fees for Whole Blood and Blood Components in Blood Service Facilities.You can download the pdf file at http://home.nbbnets.net/download.php', 
                    'thumbnail' => 'http://www.bergamopost.it/wp-content/uploads/2018/05/microscopio.jpg',
                    'author' => 'support', 'created_dt' => date('Y-m-d h:i:s')
                ],
                [
                    'id' => 2, 'title' => 'Blood Safety Indicator report period 2017',
                    'content' => 'Sudmission of BSI report period 2017 is now on going. Kindly check your BSI report history @ home.nbbents.net/bsi or click the BSI link found in the upper right corner of the page', 
                    'thumbnail' => 'https://guardian.ng/wp-content/uploads/2017/05/blood-donation.jpg',
                    'author' => 'support', 'created_dt' => date('Y-m-d h:i:s')
                ],
                [
                    'id' => 3, 'title' => 'NVBSP and BSI website is now up and running',
                    'content' => 'The NVBSP and BSI website is now up and running as of April 17, 2018', 
                    'thumbnail' => 'http://assets.change.org/photos/1/th/nr/zETHnrbIKLmOlph-1600x900-noPad.jpg?1525284995',
                    'author' => 'support', 'created_dt' => date('Y-m-d h:i:s')
                ]
                ];
        return ['status'=>'ok', 'data' => $data];
    }

    function upload(Request $request){
        $d = new Donor();
        $path = public_path() . '/uploads/';
        $id = $request->get('id');
        $d->fname = $request->get('fname');
        $d->mname = $request->get('mname');
        $d->lname = $request->get('lname');
        $seqno = $request->get('seqno');
        $d->seqno = $seqno != 'null' && $seqno != null ? $seqno : $this->generateSeqno('00001');
        // return ['data'=> $d->seqno];
        $imagePath = $path.$id.$d->fname.$d->lname.'.jpg';
        $photo = $this->getImageBase64($imagePath);
        // return ['data'=> strlen($photo)];
        // return ['data'=>$photo];
        if($photo){
            // unlink($imagePath);
            $d->donor_photo = $photo;
            // return var_dump($photo);
        }
        $d->bdate = $request->get('bdate');
        $d->gender = $request->get('gender') != 'null' ? $request->get('gender') : null;
        $d->home_no_st_blk = $request->get('nsb') != 'null' ? $request->get('nsb') : null;
        $d->home_brgy = $request->get('barangay') != 'null' ? $request->get('barangay') : null;
        $d->home_citymun = $request->get('city') != 'null' ? $request->get('city') : null;
        $d->home_prov = $request->get('province') != 'null' ? $request->get('province') : null;
        $d->home_region = $request->get('region') != 'null' ? $request->get('region') : null;
        $d->facility_cd = '00001';
        $d->created_by = 'app';
        $d->created_dt = date('Y-m-d H:i:s');
        // return ['status'=>'ok','data'=>$d];
        $d->save();
        return ['status'=>'ok', 'data' => [
            'seqno' => $d->seqno, 'id'=> $request->get('id')
        ]];
    }

    function uploadphoto(Request $request){

          $file = $request->file('file');
            // return ['data' => $file->getClientOriginalName()];
          $path = public_path() . '/uploads/';
          $file->move($path, $file->getClientOriginalName() );
          // return ['status' => 'ok', 'data' => $path.$file->getClientOriginalName()];
          // $this->resizeImage($path.$file->getClientOriginalName());
          $imagePath = $path.$file->getClientOriginalName();
          $this->resizeImage($imagePath,$imagePath);
          return ['status' => 'ok', 'data' => date('Y-m-d h:i:s')];
    }

    function getImageBase64($path){
        // $type = pathinfo($path, PATHINFO_EXTENSION);
        if(file_exists($path)){
            $data = file_get_contents($path);
            return $data;
            // return addslashes($data);
            // return base64_encode($data);
        }else{
            return null;
        }
    }

    function resizeImage($imagePath) {
        $resized = $this->compress_image($imagePath, $imagePath);
    }

    function compress_image($source_url, $destination_url, $quality = 50) {

        $info = getimagesize($source_url);

            if ($info['mime'] == 'image/jpeg')
                    $image = imagecreatefromjpeg($source_url);

            elseif ($info['mime'] == 'image/gif')
                    $image = imagecreatefromgif($source_url);

        elseif ($info['mime'] == 'image/png')
                    $image = imagecreatefrompng($source_url);

            imagejpeg($image, $destination_url, $quality);
        return $destination_url;
    }

    function generateSeqno($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = Donor::select('seqno')->whereFacilityCd($facility_cd)->orderBy('seqno','desc')->first();
            if($max){
                $max = $max->seqno;
            }else if(!$max){
                return $facility_cd.date('Y').str_pad('1',7,'0',STR_PAD_LEFT);
            }
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);
        $isExists = Donor::whereSeqno($new)->first();
        if($isExists){
            $i++;
            return $this->generateSeqno($facility_cd,$i,$max);
        }

        return $new;
    }
}

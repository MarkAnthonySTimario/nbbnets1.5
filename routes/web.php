<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test','HomeController@stocks');

Route::get('/', function () {
    return view('layout.default');
});

Route::get('/login', function () {
    return view('welcome');
})->name('login');

Route::get('/barcode/{donation_id}',function ($donation_id){
    $barcode = DNS1D::getBarcodePNG($donation_id, "C128",2,30,[0,0,0],true);
    return response($barcode, 200)->header('Content-Type','image/png');
});
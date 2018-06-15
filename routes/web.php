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

Route::get('/barcode/{donation_id}','TemplateController@barcode');
Route::get('/label','TemplateController@preview');
Route::get('/labelpreview','TemplateController@facilitypreview');
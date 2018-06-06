<?php
use App\Http\Middleware\CustomAuth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login','HomeController@login');

Route::middleware('client')->group(function(){
    Route::post('verify', 'HomeController@verify');
    Route::post('stocks', 'HomeController@stocks');
    Route::post('changepassword', 'HomeController@changepassword');

    Route::post('agencies','AgencyController@agencies');
    Route::get('agency/info/{agency_cd}','AgencyController@info');
    Route::post('agency/create','AgencyController@create');
    Route::post('agency/update/{agency_cd}','AgencyController@update');

    Route::post('mbd', 'MBDController@search');
    Route::get('mbd/info/{sched_id}', 'MBDController@info');
    Route::get('mbd/donations/{sched_id}', 'MBDController@donations');
    Route::get('mbd/shortinfo/{sched_id}', 'MBDController@shortInfo');
    Route::post('mbd/create', 'MBDController@create');
    Route::post('mbd/addDonor', 'DonationController@mbdNewDonor');
    Route::post('mbd/assignDonor', 'DonationController@mbdAssignDonor');
    Route::post('mbd/donationRemove', 'DonationController@donationRemove');
    Route::post('mbd/checkDonationIDs', 'DonationController@checkDonationIDs');
    Route::post('mbd/updateDonationDetails', 'DonationController@updateDonationDetails');
    Route::post('mbd/report', 'MBDController@report');

    Route::post('donors', 'DonorController@search');
    Route::post('donor/create', 'DonorController@create');
    Route::post('donor/update', 'DonorController@update');
    Route::get('donor/{seqno}', 'DonorController@info');
    Route::get('donor/{seqno}/photo', 'DonorController@photo');
    Route::post('walkin/create', 'DonationController@newWalkIn');
    Route::post('walkin/assignDonor', 'DonationController@walkinAssignDonor');
    Route::post('walkin', 'DonationController@walkin');
    
    
    Route::post('register/checkDonationIDs', 'DonationController@registerCheckDonationIDs');
    Route::post('donation/register', 'DonationController@register');
    Route::post('typing/list','TypingController@lists');
    Route::post('typing/save','TypingController@save');
    Route::post('processing/list','ProcessingController@lists');
    Route::post('processing/save','ProcessingController@save');
    Route::post('testing/list','TestingController@lists');
    Route::post('testing/save','TestingController@save');
    Route::post('forconfirmatory/list','TestingController@forconfirmatory');
    Route::post('forconfirmatory/discard','TestingController@discard');
    Route::post('discard/list','DiscardController@lists');
    Route::post('discard/save','DiscardController@save');
    Route::post('labeling/list','LabelController@lists');
    
    Route::get('admin/templates','TemplateController@lists');
    Route::post('admin/savetemplate','TemplateController@save');
});

Route::middleware('client')->group(function(){
    Route::get('address/regions','AddressController@regions');
    Route::get('address/provinces/{regcode}','AddressController@provinces');
    Route::get('address/cities/{provcode}','AddressController@cities');
    Route::get('address/barangays/{citycode}','AddressController@barangays');

    Route::get('keyvalues','KeyValueController@keyvalues');
    Route::get('keyvalues/nations','KeyValueController@nations');
    Route::get('keyvalues/donationtypes','KeyValueController@donationtypes');
    Route::get('keyvalues/donorstatuses','KeyValueController@donorstatuses');
    Route::get('keyvalues/collectionmethods','KeyValueController@collectionmethods');
    Route::get('keyvalues/collectionstatuses','KeyValueController@collectionstatuses');
    Route::get('keyvalues/exams','KeyValueController@exams');
    Route::get('keyvalues/discardreasons','KeyValueController@discardreasons');
    Route::get('contact/{user_id}','KeyValueController@contact_info');
    Route::post('contacts/search','KeyValueController@contact_search');

    Route::get('dhq/questions','DHQController@questions');

    Route::post('mbdselect/letter','MBDController@agencyStartWith');
    Route::get('mbdselect/agencyMBDs/{agency_cd}','MBDController@agencyMBDs');
    
    Route::get('bloodbag/components','BagComponentController@lists');
    
    Route::get('admin/facility/listsimple','AdminController@facilitylistsimple');
    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
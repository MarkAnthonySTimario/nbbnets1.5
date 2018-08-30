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
Route::get('notificationsound','HomeController@sound');

Route::post('sticker/register','StickerController@register');
Route::post('networking/distance','BloodBankNetworkingController@getDistance');

Route::middleware('client')->group(function(){
    Route::get('auc', 'AgencyUpdateController@agencies');
    Route::post('auc/remote', 'AgencyUpdateController@remote');
    Route::post('auc/distance', 'AgencyUpdateController@distance');
    Route::post('auc/distance2', 'AgencyUpdateController@distance2');
    Route::post('auc/report1', 'AgencyUpdateController@report1');
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
    Route::post('labeling/save','LabelController@save');

    Route::post('releasetoinventory/list','ReleaseToInventoryController@list');
    Route::post('releasetoinventory/save','ReleaseToInventoryController@save');
    
    Route::get('networking/facilities','BloodBankNetworkingController@facilities');
    Route::get('networking/facilities/{facility_name}','BloodBankNetworkingController@getFacilitiesWithStocks');
    Route::post('networking/intents','BloodBankNetworkingController@getIntents');
    Route::post('networking/facility','BloodBankNetworkingController@facility');
    Route::post('networking/sendintent','BloodBankNetworkingController@addIntent');
    Route::post('networking/intentavailable','BloodBankNetworkingController@intentAvailable');
    Route::post('networking/serveintent','BloodBankNetworkingController@serveIntent');
    Route::get('networking/getservedintent/{facility_cd}','BloodBankNetworkingController@getServeIntent');
    Route::get('networking/deleterequest/{facility_cd}','BloodBankNetworkingController@deletServeIntent');
    Route::post('networking/lookupunits','BloodBankNetworkingController@lookUpUnits');
    
    Route::post('admin/registerfacility','FacilityController@register');
    Route::post('admin/updatefacility','FacilityController@update');
    Route::post('admin/facility','FacilityController@info');
    Route::get('admin/facilityusers/{facility_cd}','FacilityController@users');
    Route::get('admin/templates','TemplateController@lists');
    Route::post('admin/savetemplate','TemplateController@save');
    Route::get('labeltemplate/gettemplate/{facility_cd}','TemplateController@getTemplate');
    Route::post('labeltemplate/savefacilitytemplate','TemplateController@saveFacilityTemplate');
    // Route::post('labeltemplate/checkunit','TemplateController@checkunit');
    // Route::post('labeltemplate/createdummy','TemplateController@createdummy');
    Route::post('BSI/item','BSIController@fetch');
    Route::post('available/list','BloodController@lists');
    Route::post('facility/search','FacilityController@search');
    
    Route::post('emergencypool/create', 'EmergencyPoolController@create');
    Route::get('emergencypool/get/{facility_cd}', 'EmergencyPoolController@get');

    Route::post('bsi/yesno','BSIController@yesno');
    Route::post('bsi/create','BSIController@create');
    Route::get('sticker/check/{facility_cd}/{donation_id}','StickerController@check');

    Route::get('LabelReprint/Units/{donation_id}','LabelReprintController@units');
    Route::post('LabelReprint/ReprintFired','LabelReprintController@reprintFired');

});
Route::post('bsi/exist','BSIController@exist');
Route::post('bsi/item','BSIController@fetch');


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
    Route::get('keyvalues/facilitytypes','KeyValueController@facilitytypes');
    Route::get('keyvalues/facilitycathergories','KeyValueController@facilitycathergories');
    Route::get('keyvalues/userlevels','KeyValueController@userlevels');
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

// App

Route::post('app/login','AppController@login');
Route::get('app/photo/{seqno}','AppController@photo');
Route::get('app/checkupdates/count/{last}','AppController@count');
Route::get('app/checkupdates/{rows}/{last}','AppController@checkupdates');
Route::get('app/getUpdateCount/{regcode}/{last}','AppController@getUpdateCount');
Route::get('app/getUpdate/{regcode}/{last}','AppController@getUpdate');
Route::get('app/regions','AppController@regions');
Route::get('app/provinces','AppController@provinces');
Route::get('app/cities','AppController@cities');
Route::get('app/barangays/{regcode}','AppController@barangays');
Route::get('app/news/{max}','AppController@news');
Route::post('app/upload','AppController@upload');
Route::post('app/uploadphoto','AppController@uploadphoto');
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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'HomeController@index');
//
Route::post('site', 'SiteController@store');


Route::post('site', 'SiteController@store')->middleware('auth');
Route::delete('site/{site}', 'SiteController@destroy')->middleware('auth');
Route::get('site', 'SiteController@index');
//Route::get('equipment/refresh', 'EquipmentController@refresh')->middleware('auth');;
Route::get('site/create', 'SiteController@create');
Route::get('sites.json', 'SiteController@indexJSON');

Route::get('site/{site}.json', 'SiteController@showJSON');
Route::get('site/{site}', 'SiteController@show');

Route::get('site/{site}/edit', 'SiteController@edit')->middleware('auth');;

//
Route::post('subnets', 'SubnetController@store')->middleware('auth');

Route::get('subnets', 'SubnetController@index')->middleware('auth');
Route::get('subnets/create', 'SubnetController@create')->middleware('auth');

Route::get('subnets/{subnet}', 'SubnetController@show')->middleware('auth');
Route::get('subnets/{subnet}/edit', 'SubnetController@edit')->middleware('auth');;

Route::get('log', 'LogEntryController@index')->middleware('auth');

//
Route::post('ips', 'IPController@store')->middleware('auth');

Route::get('ips', 'IPController@index')->middleware('auth');
Route::get('ips/create', 'IPController@create')->middleware('auth');

Route::get('ips/{ip}', 'IPController@show')->middleware('auth');
Route::get('ips/{ip}/edit', 'IPController@edit')->middleware('auth');;

//
Route::post('links', 'PtpLinkController@store')->middleware('auth');
Route::get('links', 'PtpLinkController@index')->middleware('auth');
Route::get('links/create', 'PtpLinkController@create')->middleware('auth');
Route::get('links/{link}', 'PtpLinkController@show')->middleware('auth');
Route::get('links/{link}/edit', 'PtpLinkController@edit')->middleware('auth');

Route::get('graphs', 'GraphController@index')->middleware('auth');
Route::get('graphs/create', 'GraphController@create')->middleware('auth');


//

Route::group(['middleware' => ['role:network_operator']], function () {

    Route::get('equipment/{equipment}/edit', 'EquipmentController@edit')->middleware('auth');;
    Route::get('equipment/refresh', 'EquipmentController@refresh')->middleware('auth');;

    Route::post('equipment', 'EquipmentController@store')->middleware('auth');
    Route::delete('equipment/{equipment}', 'EquipmentController@destroy')->middleware('auth');
    Route::get('equipment/create', 'EquipmentController@create')->middleware('auth');;


});

Route::get('equipment', 'EquipmentController@index');
Route::get('equipment/{equipment}', 'EquipmentController@show');

//http://portal.hamwan.ca/librenms/api/v0/devices/hex1.lmk.in.hamwan.ca/health/device_voltage/1
//Route::get('equipment/{equipment}/graph/{type}', 'EquipmentController@libreGetGraph');
Route::get('equipment/{equipment}/graph/{type}/{url?}', 'EquipmentController@getGraph')->where('url', '.*');

Route::get('equipment/{equipment}/denkovi/current_state.json', 'EquipmentController@doDenkoviCurrentState');

Route::get('equipment/{equipment}/{method}', 'EquipmentController@showAjax')->middleware('auth');
//

Route::get('users', 'UserController@index');
Route::get('users/{user}', 'UserController@show');

Route::post('users/{user}/perms', 'UserController@update_perms')->middleware('permission:permissions.user_change');
Route::post('users/{user}/roles', 'UserController@update_roles')->middleware('permission:roles.user_change');

Route::get('users/keys/sshkey-{callsign}.pub', 'UserController@get_pub_sshkey');



Route::post('client', 'ClientController@store')->middleware('auth');

Route::get('clients', 'ClientController@index');
Route::get('clients/refresh', 'ClientController@refresh');

Route::get('clients/{client}', 'ClientController@show');

Route::group(['middleware' => ['role:network_operator']], function () {

    Route::get('clients/{client}/snmp', 'ClientController@showSNMP');
    Route::get('client/{client}/edit', 'ClientController@edit')->middleware('auth');;
});
Route::get('clients/{client}/{method}', 'ClientController@showAjax')->middleware('auth');
//
//
//
Route::get('map', 'StatusController@map');
Route::get('map/embed', 'StatusController@mapEmbed');
//
//
//
//Route::get('static-ip', 'IPController@index');
Route::get('lease-ip', 'DhcpLeaseController@index');
Route::get('lease-ip/refresh', 'DhcpLeaseController@refresh');

Route::get('static-ip', 'StaticLeaseController@index');
Route::get('static-ip/refresh', 'StaticLeaseController@refresh');
//
//
Route::get('coverages/{site}-{direction}-{clientGain}.png', 'CoverageController@getImage');
Route::get('coverages/{site}-{direction}-{clientGain}.json', 'CoverageController@getGeoJSON');
Route::get('coverages/{site}-{direction}-{clientGain}.topo.json', 'CoverageController@getTopoJSON');
Route::get('coverages', 'CoverageController@index');


Route::get('keys', 'RsaKeyController@index');
Route::get('keys/create', 'RsaKeyController@create');
Route::post('keys', 'EquipmentController@store');

Route::get('keys/{key}', 'RsaKeyController@show');

Route::get('status.json', 'StatusController@index');
Route::get('status/icon/{type}.svg', 'StatusController@icon');

Route::get('utilities/recache-coverages', 'UtilitiesController@recacheCoverages');
Route::get('utilities/routeros-upgrade-manager', 'UtilitiesController@routerOSUpgradeManager');
Route::get('utilities/routeros-config-check', 'UtilitiesController@routerOSConfigCheck');
Route::get('utilities/frequency-planning', 'UtilitiesController@frequencyPlanning');
Route::get('utilities/frequency-planning', 'UtilitiesController@frequencyPlanning');
Route::get('utilities/equipment-snmp', 'UtilitiesController@equipmentSNMP');

Route::group(['middleware' => ['role:network_operator']], function () {

    Route::get('ping', 'UtilitiesController@ping');
});


Route::get('aim', 'UtilitiesController@aim');
Route::get('aim/rssi.php', 'UtilitiesController@aimRSSI');

Route::get('roles', 'RoleController@index');
Route::get('roles/{role}', 'RoleController@show');
Route::post('roles/{role}/perms', 'RoleController@update_perms')->middleware('permission:permissions.role_change');


Route::post('ingest-scan', 'UtilitiesController@ingestScan');

//
//
//Route::controllers([
//    'auth' => 'Auth\AuthController',
//    'password' => 'Auth\PasswordController',
//]);


Auth::routes();

//Route::get('/', 'HomeController@index')->name('home');

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

Route::get('site', 'SiteController@index');

Route::get('site/create', 'SiteController@create');
Route::get('site/{site}', 'SiteController@show');

Route::delete('site/{site}/delete', 'SiteController@delete');
//
//
Route::post('equipment', 'EquipmentController@store');
Route::get('equipment', 'EquipmentController@index');
Route::get('equipment/refresh', 'EquipmentController@refresh');
Route::get('equipment/{equipment}', 'EquipmentController@show');
Route::get('equipment/{equipment}/edit', 'EquipmentController@edit')->middleware('auth');;

Route::get('equipment/{equipment}/{method}', 'EquipmentController@showAjax')->middleware('auth');
//
Route::get('users', 'UserController@index');
Route::get('users/keys/sshkey-{callsign}.pub', 'UserController@get_pub_sshkey');



Route::get('clients', 'ClientController@index');
Route::get('clients/refresh', 'ClientController@refresh');
Route::get('clients/{client}', 'ClientController@show');
Route::get('clients/{client}/snmp', 'ClientController@showSNMP');
Route::get('clients/{client}/edit', 'ClientController@edit')->middleware('auth');;
Route::get('clients/{client}/{method}', 'ClientController@showAjax')->middleware('auth');

//
//
//
Route::get('map', 'StatusController@map');
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
Route::get('coverages', 'CoverageController@index');


Route::get('keys', 'RsaKeyController@index');
Route::get('keys/create', 'RsaKeyController@create');
Route::post('keys', 'EquipmentController@store');

Route::get('keys/{key}', 'RsaKeyController@show');


//
//
//Route::controllers([
//    'auth' => 'Auth\AuthController',
//    'password' => 'Auth\PasswordController',
//]);

Auth::routes();

//Route::get('/', 'HomeController@index')->name('home');
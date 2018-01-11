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

Route::get('/', 'HomeController@index');
Route::get('/', ['as'=>'home','uses'=>'HomeController@index']);

Route::get('register','Auth\RegisterController@showRegistrationForm');
Route::post('register','Auth\RegisterController@register');
Route::get('logout','Auth\LoginController@logout');
//Route::get('login','Auth\LoginController@showLoginForm');
//Route::post('login','Auth\LoginController@login');
Route::get('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@login']);


Route::get('forgetpassword','Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('forgetpassword','Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('resetlinksent','Auth\ForgotPasswordController@resetLinkSent');
Route::get('resetpassword/{token}','Auth\ResetPasswordController@showResetForm');
Route::post('reset','Auth\ResetPasswordController@reset');



Route::group([ 'middleware' => 'auth'], function()
{
   Route::resource('topic','TopicController');
   Route::get('camp/create/{topicnum}/{campnum}', [ 'as' => 'camp.create', 'uses' => 'TopicController@create_camp']);
   Route::post('camp/save', [ 'as' => 'camp.save', 'uses' => 'TopicController@store_camp']);
   Route::get('settings', [ 'as' => 'settings', 'uses' => 'SettingsController@index']);
   Route::post('settings/profile/update', [ 'as' => 'settings.profile.update', 'uses' => 'SettingsController@profile_update']);
   Route::get('settings/nickname', [ 'as' => 'settings.nickname', 'uses' => 'SettingsController@nickname']);
   Route::post('settings/nickname/add', [ 'as' => 'settings.nickname.add', 'uses' => 'SettingsController@add_nickname']);
});

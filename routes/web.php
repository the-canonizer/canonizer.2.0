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

Route::get('register','Auth\RegisterController@showRegistrationForm');
Route::post('register','Auth\RegisterController@register');
Route::get('logout','Auth\LoginController@logout');
Route::get('login','Auth\LoginController@showLoginForm');
Route::post('login','Auth\LoginController@login');
Route::get('forgetpassword','Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('forgetpassword','Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('resetlinksent','Auth\ForgotPasswordController@resetLinkSent');
Route::get('resetpassword/{token}','Auth\ResetPasswordController@showResetForm');
Route::post('reset','Auth\ResetPasswordController@reset');

Route::resource('topic','TopicController');

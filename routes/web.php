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


Route::get('home', ['as'=>'home','uses'=>'HomeController@index']);
Route::get('browse', ['as'=>'browse','uses'=>'HomeController@browse']);
Route::get('supportmigration', ['as'=>'supportmigration','uses'=>'HomeController@supportmigration']);

Route::post('/change-algorithm','HomeController@changeAlgorithm')->name('change-algorithm');
Route::post('/change-namespace','HomeController@changeNamespace')->name('change-namespace');


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
Route::get('topic/{id}/{campnum}', [ 'as' => 'topic', 'uses' => 'TopicController@show']);
Route::post('loadtopic','HomeController@loadtopic');
Route::get('camp/history/{id}/{campnum}', 'TopicController@camp_history');
Route::get('statement/history/{id}/{campnum}', 'TopicController@statement_history');
Route::get('topic-history/{id}', 'TopicController@topic_history');

Route::group([ 'middleware' => 'auth'], function()
{
   Route::resource('topic','TopicController');
   Route::get('camp/create/{topicnum}/{campnum}', [ 'as' => 'camp.create', 'uses' => 'TopicController@create_camp']);
   Route::post('camp/save', [ 'as' => 'camp.save', 'uses' => 'TopicController@store_camp']);
   Route::post('statement/save', [ 'as' => 'statement.save', 'uses' => 'TopicController@store_statement']);
   Route::get('settings', [ 'as' => 'settings', 'uses' => 'SettingsController@index']);
   Route::post('settings/profile/update', [ 'as' => 'settings.profile.update', 'uses' => 'SettingsController@profile_update']);
   Route::get('settings/nickname', [ 'as' => 'settings.nickname', 'uses' => 'SettingsController@nickname']);
   Route::post('settings/nickname/add', [ 'as' => 'settings.nickname.add', 'uses' => 'SettingsController@add_nickname']);
   Route::post('settings/support/add', [ 'as' => 'settings.support.add', 'uses' => 'SettingsController@add_support']);
   Route::post('settings/support/delete', [ 'as' => 'settings.support.delete', 'uses' => 'SettingsController@delete_support']);
   Route::get('manage/camp/{id}', 'TopicController@manage_camp');
   Route::get('manage/statement/{id}', 'TopicController@manage_statement');
   Route::get('manage/topic/{id}', 'TopicController@manage_topic');
   Route::get('support/{id}/{campnum}', 'SettingsController@support');
   Route::get('support', [ 'as' => 'settings.support', 'uses' =>'SettingsController@support']);
   Route::post('support-reorder', [ 'as' => 'settings.support-reorder', 'uses' =>'SettingsController@supportReorder']);
   Route::get('upload', [ 'as' => 'upload.files', 'uses' =>'UploadController@getUpload']);
   Route::post('upload', [ 'as' => 'upload.files.save', 'uses' =>'UploadController@postUpload']);
   Route::get('settings/algo-preferences', [ 'as' => 'settings.algo-preferences', 'uses' => 'SettingsController@algo']);
   Route::post('settings/algo-preferences', [ 'as' => 'settings.algo-preferences-save', 'uses' => 'SettingsController@postAlgo']);
   
});

/**
 * Routes Related to Camp Forums and threads
 */


Route::get(
    '/forum/{topicid}-{topicname}/{campnum}/threads', 
    ['uses' => 'CThreadsController@index']
);

Route::get(
    '/forum/{topicid}-{topicname}/threads',
    ['uses' => 'CThreadsController@topicindex']
);

Route::get(
    '/forum/{topicid}-{topicname}/{campnum}/threads/create', 
    ['uses' => 'CThreadsController@create']
);

Route::get(
    '/forum/{topicid}-{topicname}/{campnum}/threads/{thread}', 
    [ 'uses' => 'CThreadsController@show']
);

Route::post(
    '/forum/{topicid}-{topicname}/{campnum}/threads', 
    'CThreadsController@store'
);

Route::post(
    '/forum/{topicid}-{topicname}/{campnum}/threads/{thread}/replies', 
    ['uses' => 'ReplyController@store']
);

if(env('APP_DEBUG')){
    Route::get('/', 'HomeController@index'); /*Allow /_debuggerBar url*/
}else{
    Route::get('/{params?}', 'HomeController@index')->where('params', '(.*)');
}

Route::get('/user/supports/{user_id}','TopicController@usersupports')->name('user_supports');

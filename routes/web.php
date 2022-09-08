<?php

use Illuminate\Support\Facades\Route;

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

if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

Route::get('/admin/login', 'Admin\LoginController@getLogin');
Route::post('/admin/login', 'Admin\LoginController@postLogin')->middleware('XssSanitization');
Route::get('/archievefiles', 'Admin\ActionController@archievefiles');
Route::get('/removearchievefiles', 'Admin\ActionController@removearchievefiles');
Route::group(['prefix' => 'admin', 'middleware' => 'adminauth'], function () {
    Route::get('/', 'Admin\ManageController@getIndex');
    Route::get('/namespace/create', 'Admin\ManageController@getCreateNamespace');
    Route::post('/namespace/create', 'Admin\ManageController@postCreateNamespace')->middleware('XssSanitization');
    Route::get('/namespace/edit/{id}', 'Admin\ManageController@getUpdateNamespace');
    Route::post('/namespace/edit/{id}', 'Admin\ManageController@postUpdateNamespace')->middleware('XssSanitization');
    Route::get('/namespace/delete/{id}', 'Admin\ManageController@removeNamespace');
    Route::get('/namespace-requests', 'Admin\ManageController@getNamespaceRequests');
    Route::get('/users', 'Admin\UserController@getIndex');
    Route::get('/users/edit/{id}', 'Admin\UserController@getEdit');
    Route::post('/users/edit/{id}', 'Admin\UserController@postUpdate')->middleware('XssSanitization');
    Route::get('/namespace', 'Admin\ManageController@namespace');
    Route::get('/templates', 'Admin\TemplateController@index');
    Route::get('/template/create', 'Admin\TemplateController@create');
    Route::get('/template/edit/{id}', 'Admin\TemplateController@edit');    
    Route::get('/template/delete/{id}', 'Admin\TemplateController@destroy');
    Route::patch('/template/update/{id}', ['as'=>'template.update', 'uses'=>'Admin\TemplateController@update']);
    Route::post('/template/store', ['as'=>'template.store', 'uses'=>'Admin\TemplateController@store'])->middleware('XssSanitization');
    Route::get('/sendmail', ['as'=>'sendmail', 'uses'=>'Admin\UserController@getSendmail']);
    Route::post('/sendmail', ['as'=>'sendmail', 'uses'=>'Admin\UserController@postSendmail'])->middleware('XssSanitization');
    Route::get('/videopodcast', ['as'=>'videopodcast', 'uses'=>'Admin\VideoController@index']);
    Route::post('/videopodcast', ['as'=>'videopodcast.store', 'uses'=>'Admin\VideoController@store'])->middleware('XssSanitization');
    Route::post('/copydatabase', ['as'=>'copydatabase', 'uses'=>'Admin\ActionController@copydatabase'])->middleware('XssSanitization');
    Route::post('/copyfiles', ['as'=>'copyfiles', 'uses'=>'Admin\ActionController@copyfiles'])->middleware('XssSanitization');

    Route::get('/shares', 'Admin\SharesAlgoController@index');
    Route::get('/shares/create', 'Admin\SharesAlgoController@create');
    Route::get('/shares/edit/{id}', 'Admin\SharesAlgoController@edit');    
    Route::get('/shares/delete/{id}', 'Admin\SharesAlgoController@destroy');
    Route::patch('/shares/update/{id}', ['as'=>'shares.update', 'uses'=>'Admin\SharesAlgoController@update']);
    Route::post('/shares/store', ['as'=>'shares.store', 'uses'=>'Admin\SharesAlgoController@store'])->middleware('XssSanitization');
    Route::post('/shares/getshares', ['as'=>'shares.getshares', 'uses'=>'Admin\SharesAlgoController@getshares'])->middleware('XssSanitization');

});

Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('/privacypolicy', ['as' => 'privacypolicy', 'uses' => 'HomeController@privacypolicy']);
Route::get('/termservice', ['as' => 'termservice', 'uses' => 'HomeController@termservice']);
Route::get('browse', ['as' => 'browse', 'uses' => 'HomeController@browse']);
Route::get('supportmigration', ['as' => 'supportmigration', 'uses' => 'HomeController@supportmigration']);

Route::post('/change-algorithm', 'HomeController@changeAlgorithm')->name('change-algorithm')->middleware('XssSanitization');
Route::post('/change-namespace', 'HomeController@changeNamespace')->name('change-namespace')->middleware('XssSanitization');


Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::get('signup', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register')->middleware('XssSanitization');
Route::get('logout', 'Auth\LoginController@logout');
//Route::get('register/verify-otp', 'Auth\RegisterController@getOtpForm');
Route::get('register/verify-otp', ['as' => 'register.otp', 'uses' => 'Auth\RegisterController@getOtpForm']);
Route::post('register/verify-otp', 'Auth\RegisterController@postVerifyOtp')->middleware('XssSanitization');
Route::get('social/askemail', ['as' => 'social.askemail', 'uses' => 'SocialController@getAskEmail']);
Route::post('social/verifyemail', 'SocialController@postVerifyEmail')->middleware('XssSanitization');
//Route::get('login','Auth\LoginController@showLoginForm');
//Route::post('login','Auth\LoginController@login');
Route::get('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', [ 'as' => 'login', 'uses' => 'Auth\LoginController@login','middleware'    => 'checkstatus'])->middleware('XssSanitization');
// social login url 
Route::get('login/{provider}', 'SocialController@redirect');
Route::get('login/{provider}/callback',[ 'uses' =>'SocialController@Callback' , 'middleware' => 'checkstatus']);
Route::get('verify-otp', ['as' => 'login.otp', 'uses' => 'Auth\LoginController@getOtpForm']);
Route::post('verify-otp', 'Auth\LoginController@validateLoginOtp')->middleware('XssSanitization');

Route::get('forgotpassword', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('forgetpassword', 'Auth\ForgotPasswordController@sendResetLinkEmail')->middleware('XssSanitization');
Route::get('resetlinksent', 'Auth\ForgotPasswordController@resetLinkSent');
Route::get('resetpassword/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('reset', 'Auth\ResetPasswordController@reset')->middleware('XssSanitization');

Route::get('topic/{id}/{campnum}', [ 'as' => 'topic', 'uses' => 'TopicController@show']);
Route::get('topic.asp/{id}/{campnum}', [ 'as' => 'topic', 'uses' => 'TopicController@show']);
Route::get('topic/{id}', [ 'as' => 'topic', 'uses' => 'TopicController@show']);
Route::post('loadtopic', 'HomeController@loadtopic')->middleware('XssSanitization');
Route::get('camp/history/{id}/{campnum}', 'TopicController@camp_history');
Route::get('statement/history/{id}/{campnum}', 'TopicController@statement_history');
Route::get('topic-history/{id}', 'TopicController@topic_history');
Route::get('api/v1/getcampoutline/{topic_num}/{camp_num}/{add_supporters}', 'ApiController@getcampoutline');
Route::get('user/supports/{user_id}', 'TopicController@usersupports');
Route::post('/camp/add_subscription',['as'=>'camp.subscription','uses'=>'TopicController@add_camp_subscription'])->middleware('XssSanitization');
Route::post('/camp/add_topic_subscription',['as'=>'topic.subscription','uses'=>'TopicController@add_topic_subscription'])->middleware('XssSanitization');

Route::group([ 'middleware' => 'auth'], function() {
    Route::resource('topic', 'TopicController');
    Route::get('camp/create/{topicnum}/{campnum}', [ 'as' => 'camp.create', 'uses' => 'TopicController@create_camp']);
	Route::get('create/topic', [ 'as' => 'topic.create', 'uses' => 'TopicController@create']);
    Route::post('camp/save', [ 'as' => 'camp.save', 'uses' => 'TopicController@store_camp'])->middleware('XssSanitization');
    Route::post('statement/save', [ 'as' => 'statement.save', 'uses' => 'TopicController@store_statement'])->middleware('XssSanitization');
    Route::get('settings', [ 'as' => 'settings', 'uses' => 'SettingsController@index']);
    Route::post('settings/profile/update', [ 'as' => 'settings.profile.update', 'uses' => 'SettingsController@profile_update'])->middleware('XssSanitization');
	Route::post('settings/phone/verify', [ 'as' => 'settings.phone.verify', 'uses' => 'SettingsController@phone_verify'])->middleware('XssSanitization');
    Route::get('settings/nickname', [ 'as' => 'settings.nickname', 'uses' => 'SettingsController@nickname']);
    Route::post('settings/nickname/add', [ 'as' => 'settings.nickname.add', 'uses' => 'SettingsController@add_nickname'])->middleware('XssSanitization');
    Route::post('settings/support/add', [ 'as' => 'settings.support.add', 'uses' => 'SettingsController@add_support'])->middleware('XssSanitization');
    Route::post('settings/support/delete', [ 'as' => 'settings.support.delete', 'uses' => 'SettingsController@delete_support'])->middleware('XssSanitization');
    Route::get('manage/camp/{id}', 'TopicController@manage_camp');
    Route::get('manage/statement/{id}', 'TopicController@manage_statement');
    Route::get('create/statement/{topic_num}/{camp_num}', 'TopicController@create_statement');
    Route::get('manage/topic/{id}', 'TopicController@manage_topic');
    Route::get('support/{id}/{campnum}', 'SettingsController@support');
    Route::get('support', [ 'as' => 'settings.support', 'uses' => 'SettingsController@support']);
    Route::post('support-reorder', [ 'as' => 'settings.support-reorder', 'uses' => 'SettingsController@supportReorder'])->middleware('XssSanitization');
    Route::get('upload', [ 'as' => 'upload.files', 'uses' => 'UploadController@getUpload']);
    Route::post('upload', [ 'as' => 'upload.files.save', 'uses' => 'UploadController@postUpload'])->middleware('XssSanitization');
    Route::get('settings/algo-preferences', [ 'as' => 'settings.algo-preferences', 'uses' => 'SettingsController@algo']);
    Route::post('settings/algo-preferences', [ 'as' => 'settings.algo-preferences-save', 'uses' => 'SettingsController@postAlgo'])->middleware('XssSanitization');
    Route::post('statement/preview', [ 'as' => 'statement.preview', 'uses' => 'TopicController@preview_statement'])->middleware('XssSanitization');
    //change password
    Route::get('settings/changepassword', [ 'as' => 'settings.changepassword', 'uses' => 'SettingsController@getChangePassword']);
    Route::post('settings/changepassword', [ 'as' => 'settings.changepassword.save', 'uses' => 'SettingsController@postChangePassword'])->middleware('XssSanitization');
    Route::get('settings/blockchain', [ 'as' => 'settings.blockchain', 'uses' => 'SettingsController@blockchain']);
    Route::get('settings/sociallinks', [ 'as' => 'settings.sociallinks', 'uses' => 'SettingsController@sociallinks']);
    Route::post('settings/save-ether-address', [ 'as' => 'settings.save-ether-address', 'uses' => 'SettingsController@postSaveEtherAddress'])->middleware('XssSanitization');
    Route::post('statement/agreetochange', 'TopicController@statement_agreetochange')->middleware('XssSanitization');
    Route::post('graceperiod/notify_change', 'TopicController@notify_change')->middleware('XssSanitization');
    //news feed
    Route::get('/addnews/{topicnum}/{campnum}',['as'=>'addnews','uses'=>'NewsFeedController@create']);
    Route::post('/newsfeed/save',['as'=>'newsfeed.save','uses'=>'NewsFeedController@store'])->middleware('XssSanitization');
    Route::get('/editnews/{topicnum}/{campnum}',['as'=>'newsfeed.edit','uses'=>'NewsFeedController@edit'])->middleware('XssSanitization');
    Route::post('/newsfeed/update',['as'=>'newsfeed.update','uses'=>'NewsFeedController@update'])->middleware('XssSanitization');
    Route::get('/newsfeed/delete/{id}', 'NewsFeedController@destroy');
    Route::post('/deactivateuser',['as'=>'social.deactivateuser','uses'=>'SocialController@deactivateuser'])->middleware('XssSanitization');
    Route::post('/delete_social_link',['as'=>'social.delete_social_link','uses'=>'SocialController@delete'])->middleware('XssSanitization');
    Route::get('remove/mysupport/{topicnum}/{campnum}/{nickid}', [ 'as' => 'settings.support.remove', 'uses' => 'SettingsController@remove_support']);
});

Route::get('topic.asp/{id}', [ 'as' => 'topic', 'uses' => 'TopicController@show']);

Route::get(
    '/forum/{topicid}-{topicname}/{campnum}/threads', ['uses' => 'CThreadsController@index']
);

Route::get(
  '/forum/{topicid}-{topicname}/threads', ['uses' => 'CThreadsController@topicindex']
);

Route::get(
    '/forum/{topicid}-{topicname}/{campnum}/threads/create', ['uses' => 'CThreadsController@create']
);

Route::get(
    '/forum/{topicid}-{topicname}/{campnum}/threads/{thread}-{reply_id?}', [ 'uses' => 'CThreadsController@show']
);

Route::get(
  '/forum/{topicname}/{campnum}/threads/{thread}/edit', [ 'uses' => 'CThreadsController@edit']
);

Route::post(
  '/forum/{topicname}/{campnum}/threads/{thread}/edit', [ 'uses' => 'CThreadsController@save_title']
);

Route::post(
    '/forum/{topicid}-{topicname}/{campnum}/threads', 'CThreadsController@store'
)->middleware('XssSanitization');

Route::post(
    '/forum/{topicid}-{topicname}/{campnum}/threads/{thread}/replies', ['uses' => 'ReplyController@store']
)->middleware('XssSanitization');

if (env('APP_DEBUG')) {
    Route::get('/', 'HomeController@index');
} else {
    Route::get('/{params?}', 'HomeController@index')->where('params', '(.*)');
}
Route::get('user/supports/{user_id}', 'TopicController@usersupports')->name('user_supports');

Route::get('/topic/notifysupporter',function(){
    \Artisan::call('notify:supporters');
});

Route::get('getVerificationCode', 'Auth\ForgotPasswordController@showVerificationCodeForm');
Route::post('verifyCode', 'Auth\ForgotPasswordController@getVerificationCode')->middleware('XssSanitization');





// Fixes #1205 when a post method is being accesed through get it will be redirected back
Route::get('{any}', function () {return redirect()->back();})->where('any', '.*');
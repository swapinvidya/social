<?php

use App\Http\Controllers\FacebookController;
use App\Http\Controllers\testController;
use App\Http\Controllers\TwitterController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Auth;
use Atymic\Twitter\Facade\Twitter;
use Illuminate\Routing\RouteGroup;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/privacypolicy',function(){
    return view('privacypolicy');
});

Route::get('/tandc',function(){
    return view('tandc');
});

Route::get('/profile','profileController@profile');
Route::post('/profile_update','profileController@profile_update');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/getNotifications','HomeController@getNotification');

Route::post('/continue_demo','HomeController@postDemo');

Route::get('/gateway_config','GatewayController@index');

Route::get('/package_config', 'PackageController@index');
Route::post('/add_package','PackageController@add');
//Route::post('/mod_save/{id}','PackageController@mod');
Route::post('/edit_package','PackageController@edit_service');
Route::post('/package_del','PackageController@delete_service');

Route::get('/service_config', 'ServiceController@index');
Route::post('/add_service','ServiceController@add');
Route::post('/mod_save/{id}','ServiceController@mod');

Route::post('/edit_service','ServiceController@edit_service');
Route::post('/service_del','ServiceController@delete_service');

Route::get('/connect','AccountsController@connect_account');
Route::get('/manage','AccountsController@manage_account');

Route::post('/t', 'testController@fb');
Route::get('/fb', 'testController@index');

Route::get('/package_buy','payController@buy');
Route::post('/indipay/response','payController@response');

Route::get('/connect/{id}','AyrshareController@connect');

Route::get('/create_posts', 'PostController@index');

Route::post('/dd', 'PostController@post');

Route::post('/tt', 'PostController@test');
Route::post('/fbp_del', 'PostController@delete_post');




Route::group(['prefix' => 'auth/facebook', 'middleware' => 'auth'], function () {
    Route::get('/', [\App\Http\Controllers\SocialController::class, 'redirectToProvider']);
    Route::get('/callback', [\App\Http\Controllers\SocialController::class, 'handleProviderCallback']);
});

Route::get('/fb_name', 'FacebookController@fb_connect');
//Route::get('/dd', 'FacebookController@fb_post');


Route::get('/create_account_fb','AccountsController@create_account_fb');
Route::post('/save_account', 'AccountsController@save_account');
Route::get('/fbp_refresh','SocialController@fbp_refresh');

//twitter
Route::get('/tweet', function()
{
    return Twitter::getUserTimeline(['screen_name' => 'thujohn', 'count' => 20, 'response_format' => 'json']);
    // return Twitter::postTweet(array('status' => 'Tweet sent using Laravel and the Twitter API!', 'format' => 'json'));
});

Route::get('twitter/login', 'TwitterController@twitter_login')->name('twitter.login');
Route::get('twitter/callback', 'TwitterController@twitter_callback')->name('twitter.callback');

Route::get('twitter/error', ['as' => 'twitter.error', function () {
    // Something went wrong, add your own error handling here
}]);

Route::get('twitter/logout', ['as' => 'twitter.logout', function () {
    Session::forget('access_token');

    return Redirect::to('/')->with('notice', 'You\'ve successfully logged out!');
}]);


Route::get('/image_editor' , 'PostController@image_editor');

Route::get('/fb_pages/get', 'AccountsController@fb_pages');
Route::get('/fb_groups/get', 'AccountsController@fb_groups');

Route::get('/fb_page/get', 'AccountsController@fb_page');
Route::get('/fb_group/get', 'AccountsController@fb_group');
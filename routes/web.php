<?php

use App\Http\Controllers\testController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/getNotifications','HomeController@getNotification');

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

Route::post('/t', 'testController@fb');
Route::get('/fb', 'testController@index');
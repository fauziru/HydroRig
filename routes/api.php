<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('oauth/token', 'Api\Auth\AccessTokenController@issueToken');

Route::group(['prefix' => 'v1'], function () {

    // auth
    Route::get('tes1', 'Api\Auth\UsersController@tes');
    Route::post('login', 'Api\Auth\LoginController@login');
    Route::post('refresh', 'Api\Auth\LoginController@refresh');
    Route::post('register', 'Api\Auth\UsersController@register');
    Route::post('registrasiKey', 'Api\Auth\UsersController@checkRegisterKey');

    Route::middleware(['auth:api'])->group(function () {
        Broadcast::routes();
        // notification
        // Route::get('unreadnotification', 'Api\NotificationController@index');
        Route::prefix('notification')->group(function () {
            Route::get('read/{id}', 'Api\NotificationController@markAsRead');
            Route::get('unread', 'Api\NotificationController@index');
            Route::get('readall', 'Api\NotificationController@readall');
            Route::get('all/{page}', 'Api\NotificationController@allpaginate');
            Route::get('sensor/{page}', 'Api\NotificationController@sensorpaginate');
            Route::get('activity/{page}', 'Api\NotificationController@adminpaginate');
        });
        // sensor
        Route::prefix('sensor')->group(function () {
            Route::get('option', 'Api\SensorController@widget');
            Route::get('/', 'Api\SensorController@index');
            Route::post('/', 'Api\SensorController@store');
            Route::put('/{sensor}', 'Api\SensorController@put');
            Route::delete('/{sensor}', 'Api\SensorController@destroy');
            Route::get('connect-status/{sensor}', 'Api\SensorController@connectStatus');
            Route::get('connect-status', 'Api\SensorController@connectStatusAll');
        });
        // reader sensor route
        Route::prefix('sensor-nutrisi')->group(function () {
            Route::get('detail/{sensor}/{filter}/{from?}/{to?}', 'Api\ReadNutrisiController@showDetail');
            Route::get('widget/{sensor}', 'Api\ReadNutrisiController@showWidget');
        });
        // user
        Route::prefix('user')->group(function () {
            Route::get('/', 'Api\Auth\UsersController@index');
            Route::get('/{user}', 'Api\Auth\UsersController@show');
            Route::get('get/count', 'Api\Auth\UsersController@countUser');
        });
        // setting
        Route::prefix('setting')->group(function () {
            Route::get('/{type}', 'Api\SettingController@show');
            Route::get('/generate/{type}', 'Api\SettingController@generate');
        });
    });

    Route::get('sensor-nutrisi/{sensor}/read/{read}', 'Api\ReadNutrisiController@store')->middleware('last_read', 'api_key');

    // for auth user only
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('logout', 'Api\Auth\LoginController@logoutApi');
        Route::get('details', 'Api\Auth\UsersController@details');
    });
});

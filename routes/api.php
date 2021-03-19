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

    Route::get('tes', function () {
        return 'get';
    });

    // auth
    Route::get('tes1', 'Api\Auth\UsersController@tes');
    Route::post('login', 'Api\Auth\LoginController@login');
    Route::post('refresh', 'Api\Auth\LoginController@refresh');
    Route::post('register', 'Api\Auth\UsersController@register');

    Route::middleware(['auth:api'])->group(function () {
        // notification
        // Route::get('unreadnotification', 'Api\NotificationController@index');
        Route::prefix('notification')->group(function () {
            Route::get('read/{id}', 'Api\NotificationController@markAsRead');
            Route::get('unread', 'Api\NotificationController@index');
            Route::get('readall', 'Api\NotificationController@readall');
            Route::get('all/{page}', 'Api\NotificationController@allpaginate');
            Route::get('order/{page}', 'Api\NotificationController@orderspaginate');
            Route::get('message/{page}', 'Api\NotificationController@messagespaginate');
            Route::get('review/{page}', 'Api\NotificationController@reviewspaginate');
            Route::get('adminactivity/{page}', 'Api\NotificationController@adminpaginate');
            Route::get('sendNotif', 'Api\OrderController@tesNotif');
        });

        Route::prefix('sensor-nutrisi')->group(function () {
           Route::get('{sensor}/read/{read}', 'Api\SensorController@store');
        });
    });

    // for auth user only
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('logout', 'Api\Auth\LoginController@logoutApi');
        Route::get('details', 'Api\Auth\UsersController@details');
    });
});

<?php

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
    // image

    Route::get('public/{storage}/{fileName}', 'Api\DashboardController@showImage');

    Route::middleware(['auth:api'])->group(function () {
        Broadcast::routes();
        // notification
        // Route::get('unreadnotification', 'Api\NotificationController@index');
        Route::prefix('notification')->group(function () {
            Route::get('tes', 'Api\NotificationController@tes');
            Route::get('unread', 'Api\NotificationController@index');
            Route::get('readall', 'Api\NotificationController@readall');
            Route::get('read/{id}', 'Api\NotificationController@markAsRead');
            Route::get('all/{page}', 'Api\NotificationController@allpaginate');
            Route::get('sensor/{page}', 'Api\NotificationController@sensorpaginate');
            Route::get('activity/{page}', 'Api\NotificationController@adminpaginate');
            Route::post('subscribe', 'Api\NotificationController@subscribePushNotification');
            Route::get('tes-push-notifikasi', 'Api\NotificationController@tesPushNotification');
        });

        // node
        Route::prefix('node')->group(function () {
            Route::get('/{node}', 'Api\NodeController@show');
            Route::get('/', 'Api\NodeController@index');
            Route::post('/', 'Api\NodeController@store');
            Route::put('/{node}', 'Api\NodeController@update');
            Route::delete('/{node}', 'Api\NodeController@destroy');
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
        Route::prefix('read')->group(function () {
            // Route::post('/{node}', 'Api\ReadController@store');
            Route::get('detail/{sensor}/{filter}/{from?}/{to?}', 'Api\ReadController@showDetail');
            // Route::get('widget/{sensor}', 'Api\ReadController@showWidget');
        });
        // user
        Route::prefix('user')->group(function () {
            Route::get('/', 'Api\Auth\UsersController@index');
            Route::get('/{user}', 'Api\Auth\UsersController@show');
            Route::get('get/count', 'Api\Auth\UsersController@countUser');
            Route::post('/avatar', 'Api\Auth\UsersController@storeAvatar');
            Route::put('/', 'Api\Auth\UsersController@update');
            Route::post('upgrade-admin/{user}', 'Api\Auth\UsersController@upgradeAdmin');
            Route::post('downgrade-admin/{user}', 'Api\Auth\UsersController@downgradeAdmin');
        });
        // setting
        Route::prefix('setting')->group(function () {
            Route::get('/{type}', 'Api\SettingController@show');
            Route::get('/generate/{type}', 'Api\SettingController@generate');
        });
        // tipe_sensor
        Route::prefix('tipeSensor')->group(function () {
            Route::get('/', 'Api\TipeSensorController@index');
        });
        // update layout
        Route::prefix('layout')->group(function () {
            Route::get('/', 'Api\LayoutController@index');
            Route::post('/', 'Api\LayoutController@store');
            Route::put('/{layout}', 'Api\LayoutController@change');
            Route::delete('/{layout}', 'Api\LayoutController@destroy');
        });
    });

    // data sensor logger
    // Route::get('read/{sensor}', 'Api\ReadController@store')->middleware('last_read', 'api_key');
    Route::get('read/{node}', 'Api\ReadController@store')->middleware('api_key');
    Route::get('meta', 'Api\SettingController@getMeta')->middleware('api_key');
    Route::post('cek-email', 'Api\Auth\UsersController@cekEmail')->middleware('api_key');

    // for auth user only
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('logout', 'Api\Auth\LoginController@logoutApi');
        Route::get('details', 'Api\Auth\UsersController@details');
    });
});

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
// tes
// Route::get('payment/active/{payment}', 'Api\PaymentMethodController@stat');
// Guest routes
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('index');
    });
    Route::get('/sBVdRQzlgOlu2IhGB8SeIYiN6M4VFAoZ', function () {
        return view('auth.register');
    });
});

Auth::routes(['register' => false]);
// store register
Route::post('/register', 'Auth\RegisterController@register');

Route::prefix('notification')->group(function () {
    Route::get('/', 'HomeController@indexNotification')->name('notification.index');
    Route::get('read/{id}', 'Api\NotificationController@markAsRead')->name('read.notification');
    Route::get('unread', 'Api\NotificationController@index');
    Route::get('readall', 'Api\NotificationController@readall');
    Route::get('all/{page}', 'Api\NotificationController@allpaginate');
    Route::get('order/{page}', 'Api\NotificationController@orderspaginate');
    Route::get('message/{page}', 'Api\NotificationController@messagespaginate');
    Route::get('review/{page}', 'Api\NotificationController@reviewspaginate');
    Route::get('adminactivity/{page}', 'Api\NotificationController@adminpaginate');
});


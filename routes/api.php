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
Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::group(['prefix' => 'v1'], function () {

    Route::get('tes', function () {
        return 'get';
    });

  // auth
  Route::get('tes1', 'Api\Auth\UsersController@tes');
  Route::post('login', 'Api\Auth\LoginController@login');
  Route::post('refresh', 'Api\Auth\LoginController@refresh');
  Route::post('register', 'Api\Auth\UsersController@register');

  Route::middleware(['api_key'])->group(function () {
    // category
    Route::get('categories', 'Api\CategoriesController@indexCat');
    Route::get('category/{id}', 'Api\CategoriesController@detailCat');
    Route::get('subcategories', 'Api\CategoriesController@indexSubCat');
    Route::get('subcategory/{id}', 'Api\CategoriesController@detailSubCat');
    Route::get('childcategory', 'Api\CategoriesController@indexChCat');

    // location
    Route::get('provinsis', 'Api\LocationController@indexProvinsi');
    Route::get('provinsi/{id}', 'Api\LocationController@detailProvinsi');
    Route::get('kabupatens', 'Api\LocationController@indexKabupaten');
    Route::get('kabupaten/{id}', 'Api\LocationController@detailKabupaten');
    Route::get('kecamatans', 'Api\LocationController@indexKecamatan');
    Route::get('kecamatan/{id}', 'Api\LocationController@detailKecamatan');
    Route::get('kelurahans', 'Api\LocationController@indexKelurahan');


    // notification
    Route::middleware(['auth:api'])->group(function () {
        // Route::get('unreadnotification', 'Api\NotificationController@index');
        Route::get('read/{id}', 'Api\NotificationController@markAsRead');
        Route::get('unread', 'Api\NotificationController@index');
        Route::get('readall', 'Api\NotificationController@readall');
        Route::get('all/{page}', 'Api\NotificationController@allpaginate');
        Route::get('order/{page}', 'Api\NotificationController@orderspaginate');
        Route::get('message/{page}', 'Api\NotificationController@messagespaginate');
        Route::get('review/{page}', 'Api\NotificationController@reviewspaginate');
        Route::get('adminactivity/{page}', 'Api\NotificationController@adminpaginate');
    });
    Route::get('sendNotif', 'Api\OrderController@tesNotif');
  });

  // for auth user only
  Route::group(['middleware' => 'auth:api'], function(){
    Route::get('logout', 'Api\Auth\LoginController@logoutApi');
    Route::get('details', 'Api\Auth\UsersController@details');
  });
});

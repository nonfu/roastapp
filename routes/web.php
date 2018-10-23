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

Route::get('/', 'Web\AppController@getApp');

Route::get( '/auth/{social}', 'Web\AuthenticationController@getSocialRedirect' )
    ->middleware('guest');

Route::get( '/auth/{social}/callback', 'Web\AuthenticationController@getSocialCallback' )
    ->middleware('guest');

Route::get( '/logout', 'Web\AppController@getLogout' )
    ->name('logout');

Route::get('regeocode', function () {
    return \App\Utilities\GaodeMaps::findClosestCity('上海', 121.49974000, 31.23985300);
});

Route::get('/cafe/{id}', 'API\CafesController@getCafe');

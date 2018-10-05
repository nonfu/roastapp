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

Route::get('/', 'Web\AppController@getApp')
    ->middleware('auth');

Route::get('/login', 'Web\AppController@getLogin' )
    ->name('login')
    ->middleware('guest');

Route::get( '/auth/{social}', 'Web\AuthenticationController@getSocialRedirect' )
    ->middleware('guest');

Route::get( '/auth/{social}/callback', 'Web\AuthenticationController@getSocialCallback' )
    ->middleware('guest');

Route::get( '/logout', 'Web\AppController@getLogout' )
    ->name('logout');

Route::get('geocode', function () {
    return \App\Utilities\GaodeMaps::geocodeAddress('天城路1号', '杭州', '浙江');
});

Route::get('/cafe/{id}', 'API\CafesController@getCafe');

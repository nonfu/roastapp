<?php

use Illuminate\Http\Request;

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

// 公有路由，无需登录即可访问
Route::group(['prefix' => 'v1'], function () {

    Route::get('/user', 'API\UsersController@getUser');

    /*
     |-------------------------------------------------------------------------------
     | Get All Cafes
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes
     | Controller:     API\CafesController@getCafes
     | Method:         GET
     | Description:    Gets all of the cafes in the application
    */
    Route::get('/cafes', 'API\CafesController@getCafes');

    /*
     |-------------------------------------------------------------------------------
     | Get An Individual Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes/{id}
     | Controller:     API\CafesController@getCafe
     | Method:         GET
     | Description:    Gets an individual cafe
    */
    Route::get('/cafes/{id}', 'API\CafesController@getCafe');

    /*
     |-------------------------------------------------------------------------------
     | Get All Brew methods
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/brew-methods
     | Controller:     API\BrewMethodsController@getBrewMethods
     | Method:         GET
     | Description:    Gets all of the brew methods in the application
    */
    Route::get('/brew-methods', 'API\BrewMethodsController@getBrewMethods');

    /*
    |-------------------------------------------------------------------------------
    | Search Tags
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/tags
    | Controller:     API\TagsController@getTags
    | Method:         GET
    | Description:    Searches the tags if a query is set otherwise returns all tags
    */
    Route::get('/tags', 'API\TagsController@getTags');

    /*
    |-------------------------------------------------------------------------------
    | Gets All Cities
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cities
    | Controller:     API\CitiesController@getCities
    | Method:         GET
    | Description:    Get all cities
    */
    Route::get('/cities', 'API\CitiesController@getCities');

    /*
    |-------------------------------------------------------------------------------
    | Gets An Individual City
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cities/{slug}
    | Controller:     API\CitiesController@getCity
    | Method:         GET
    | Description:    Gets an individual city
    */
    Route::get('/cities/{slug}', 'API\CitiesController@getCity');
});

// 私有路由，需要登录才能访问
Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {

    /*
     |-------------------------------------------------------------------------------
     | Adds a New Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes
     | Controller:     API\CafesController@postNewCafe
     | Method:         POST
     | Description:    Adds a new cafe to the application
    */
    Route::post('/cafes', 'API\CafesController@postNewCafe');

    Route::post('/cafes/{id}/like', 'API\CafesController@postLikeCafe');
    Route::delete('/cafes/{id}/like', 'API\CafesController@deleteLikeCafe');

    /*
    |-------------------------------------------------------------------------------
    | Adds Tags To A Cafe
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cafes/{id}/tags
    | Controller:     API\CafesController@postAddTags
    | Method:         POST
    | Description:    Adds tags to a cafe for a user
    */
    Route::post('/cafes/{id}/tags', 'API\CafesController@postAddTags');

    /*
    |-------------------------------------------------------------------------------
    | Deletes A Cafe Tag
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cafes/{id}/tags/{tagID}
    | Controller:     API\CafesController@deleteCafeTag
    | Method:         DELETE
    | Description:    Deletes a tag from a cafe for a user
    */
    Route::delete('/cafes/{id}/tags/{tagID}', 'API\CafesController@deleteCafeTag');

    /*
    |-------------------------------------------------------------------------------
    | 更新用户个人信息
    |-------------------------------------------------------------------------------
    | 请求URL:     /api/v1/user
    | 控制器方法:   API\UsersController@putUpdateUser
    | 请求方式:     PUT
    | 功能描述:     更新认证用户的个人信息
    */
    Route::put('/user', 'API\UsersController@putUpdateUser');
});

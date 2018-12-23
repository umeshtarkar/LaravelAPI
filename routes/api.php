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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1/session')->namespace('Api')->group(function(){
    
    Route::post('user/create','ApiController@create');
    Route::post('user/update','ApiController@update');
    Route::post('user/status','ApiController@toggleUserStatus');

    Route::post('news/create','ApiController@createNews');
    Route::post('news/update','ApiController@updateNews');
    Route::post('news/status','ApiController@toggleNewsStatus');
    
    Route::post('category/create','ApiController@createCategory');
    Route::post('category/update','ApiController@updateCategory');
    Route::post('category/status','ApiController@toggleCategoryStatus');
    
    Route::post('admin/create','ApiController@createAdmin');
    Route::post('admin/update','ApiController@updateAdmin');
    Route::post('admin/status','ApiController@toggleAdminStatus');
    

});

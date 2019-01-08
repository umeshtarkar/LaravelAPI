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
    
    Route::post('user/create','UserController@create');
    Route::post('user/update','UserController@update');
    Route::post('user/status','UserController@toggleUserStatus');

    Route::get('news','NewsController@getNews');
    Route::get('news/{id}','NewsController@getNewsDetail');
    Route::post('news/create','NewsController@createNews');
    Route::post('news/update','NewsController@updateNews');
    Route::post('news/status','NewsController@toggleNewsStatus');
    
    Route::get('category','NewsCategoryController@getCategory');
    Route::get('category/{id}','NewsCategoryController@getCategoryDetail');
    Route::post('category/create','NewsCategoryController@createCategory');
    Route::post('category/update','NewsCategoryController@updateCategory');
    Route::post('category/status','NewsCategoryController@toggleCategoryStatus');
    
    Route::post('admin/login','ApiController@signin');
    Route::post('admin/logout','ApiController@signout');
    Route::post('admin/create','ApiController@createAdmin');
    Route::post('admin/update','ApiController@updateAdmin');
    Route::post('admin/status','ApiController@toggleAdminStatus');
    

});

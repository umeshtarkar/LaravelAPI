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
    
    Route::get('user/list','UserController@getUsers');
    Route::get('user/count','UserController@getUsersCount');
    Route::get('user/{id}','UserController@getUserDetail');
    Route::post('user/create','UserController@create');
    Route::post('user/update','UserController@update');
    Route::post('user/status','UserController@toggleUserStatus');

    Route::get('user-exam/list','UserExamController@getUserExams');
    Route::get('user-exam/{id}','UserExamController@getUserExamDetail');
    Route::post('user-exam/create','UserExamController@createUserExam');
    Route::post('user-exam/update','UserExamController@updateUserExam');

    Route::get('news','NewsController@getNews');
    Route::get('news/count','NewsController@getNewsCount');
    Route::get('news/{id}','NewsController@getNewsDetail');
    Route::post('news/create','NewsController@createNews');
    Route::post('news/update','NewsController@updateNews');
    Route::post('news/status','NewsController@toggleNewsStatus');
    
    Route::get('category','NewsCategoryController@getCategory');
    Route::get('category/count','NewsCategoryController@getCategoryCount');
    Route::get('category/{id}','NewsCategoryController@getCategoryDetail');
    Route::get('category/{id}/news','NewsCategoryController@getCategoryNews');
    Route::post('category/create','NewsCategoryController@createCategory');
    Route::post('category/update','NewsCategoryController@updateCategory');
    Route::post('category/status','NewsCategoryController@toggleCategoryStatus');
    
    Route::post('admin/login','ApiController@signin');
    Route::post('admin/logout','ApiController@signout');
    Route::post('admin/create','ApiController@createAdmin');
    Route::post('admin/update','ApiController@updateAdmin');
    Route::post('admin/status','ApiController@toggleAdminStatus');

    Route::get('article/list','CPDArticleController@getCPDArticles');
    Route::get('article/count','CPDArticleController@getCPDArticlesCount');
    Route::get('article/{id}','CPDArticleController@getCPDArticleDetail');
    Route::post('article/create','CPDArticleController@createCPDArticle');
    Route::post('article/update','CPDArticleController@updateCPDArticle');
    Route::post('article/status','CPDArticleController@toggleCPDArticle');
    
    Route::get('exam/list','ExamController@getExams');
    Route::get('exam/{id}','ExamController@getExamDetail');
    Route::post('exam/create','ExamController@createExam');
    Route::post('exam/update','ExamController@updateExam');
    Route::post('exam/status','ExamController@toggleExamStatus');

    Route::get('exam-question/list','ExamQuestionController@getExamQuest');
    Route::get('exam-question/{id}','ExamQuestionController@getExamQuestDetail');
    Route::post('exam-question/create','ExamQuestionController@createExamQuest');
    Route::post('exam-question/update','ExamQuestionController@updateExamQuest');
    
    Route::get('sale/list','SaleController@getSale');
    Route::get('sale/count','SaleController@getSaleCount');
    Route::get('sale/{id}','SaleController@getSaleDetail');
    Route::post('sale/create','SaleController@createSale');
    Route::post('sale/update','SaleController@updateSale');
    Route::post('sale/status','SaleController@toggleSaleStatus');

    Route::get('vacancy/list','VacancyController@getVacancy');
    Route::get('vacancy/count','VacancyController@getVacancyCount');
    Route::get('vacancy/{id}','VacancyController@getVacancyDetail');
    Route::post('vacancy/create','VacancyController@createVacancy');
    Route::post('vacancy/update','VacancyController@updateVacancy');
    Route::post('vacancy/status','VacancyController@toggleVacancyStatus');
});

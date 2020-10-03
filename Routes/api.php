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

/**
 * Admin API Routes
 */
Route::group(['middleware' => 'auth:api'], static function () {
    Route::post('/page', 'Api\PageController@create');
    Route::get('/page', 'Api\PageController@get');
    Route::get('/page/{id}', 'Api\PageController@getOne');
    Route::delete('/page/{id}', 'Api\PageController@delete');
    Route::patch('/page/{id}', 'Api\PageController@update');

    Route::post('/template', 'Api\TemplateController@create');
    Route::get('/template', 'Api\TemplateController@get');
    Route::get('/template/{id}', 'Api\TemplateController@getOne');
    Route::delete('/template/{id}', 'Api\TemplateController@delete');
    Route::patch('/template/{id}', 'Api\TemplateController@update');

    Route::post('/navigation', 'Api\NavigationController@create');
    Route::get('/navigation', 'Api\NavigationController@get');
    Route::get('/navigation/{id}', 'Api\NavigationController@getOne');
    Route::delete('/navigation/{id}', 'Api\NavigationController@delete');
    Route::patch('/navigation/{id}', 'Api\NavigationController@update');

    Route::post('/user', 'Api\UserController@create');
    Route::get('/user', 'Api\UserController@get');
    Route::get('/user/{id}', 'Api\UserController@getOne');
    Route::delete('/user/{id}', 'Api\UserController@delete');
    Route::patch('/user/{id}', 'Api\UserController@update');
    Route::patch('/user/authed', 'Api\UserController@getAuthed');

    Route::post('/product', 'Api\ProductController@create');
    Route::get('/product', 'Api\ProductController@get');
    Route::get('/product/{id}', 'Api\ProductController@getOne');
    Route::get('/product/load/url/{url}', 'Api\ProductController@getByUrl');
    Route::delete('/product/{id}', 'Api\ProductController@delete');
    Route::patch('/product/{id}', 'Api\ProductController@update');

    Route::post('/product-category', 'Api\ProductCategoryController@create');
    Route::get('/product-category', 'Api\ProductCategoryController@get');
    Route::get('/product-category/{id}', 'Api\ProductCategoryController@getOne');
    Route::delete('/product-category/{id}', 'Api\ProductCategoryController@delete');
    Route::patch('/product-category/{id}', 'Api\ProductCategoryController@update');

    Route::get('/component', 'Api\ComponentController@get');
    Route::patch('/component/{id}', 'Api\ComponentController@update');

    Route::post('/image/upload','Api\FileController@imageUpload');

    Route::get('/module', 'Api\ModuleController@get');

//    Route::post('/form', 'Api\FormController@submit');
});

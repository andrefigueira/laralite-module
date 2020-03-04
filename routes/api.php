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

Route::post('/user', 'Api\UserController@create');
Route::get('/user', 'Api\UserController@get');
Route::get('/user/{id}', 'Api\UserController@getOne');
Route::delete('/user/{id}', 'Api\UserController@delete');
Route::patch('/user/{id}', 'Api\UserController@update');

Route::get('/component', 'Api\ComponentController@get');

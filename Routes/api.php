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
    Route::post('/user/data', 'Api\UserController@data');

    Route::post('/roles', 'Api\RolesController@create');
    Route::get('/roles', 'Api\RolesController@get');
    Route::get('/roles/{id}', 'Api\RolesController@getOne');
    Route::delete('/roles/{id}', 'Api\RolesController@delete');
    Route::patch('/roles/{id}', 'Api\RolesController@update');

    Route::post('/permissions', 'Api\PermissionsController@create');
    Route::get('/permissions', 'Api\PermissionsController@get');
    Route::get('/permissions/{id}', 'Api\PermissionsController@getOne');
    Route::delete('/permissions/{id}', 'Api\PermissionsController@delete');
    Route::patch('/permissions/{id}', 'Api\PermissionsController@update');

    Route::get('/component', 'Api\ComponentController@get');
    Route::patch('/component/{id}', 'Api\ComponentController@update');

    Route::post('/form', 'Api\FormController@submit');
});

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

Route::get('/component', 'Api\ComponentController@get');

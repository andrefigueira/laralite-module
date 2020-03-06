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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin', 'Admin\AdminController@home');
    Route::get('/admin/home', 'Admin\AdminController@home');

    Route::get('/admin/pages', 'Admin\PagesController@index');
    Route::get('/admin/pages/create', 'Admin\PagesController@create');
    Route::get('/admin/pages/edit/{id}', 'Admin\PagesController@edit');

    Route::get('/admin/templates', 'Admin\TemplatesController@index');
    Route::get('/admin/templates/create', 'Admin\TemplatesController@create');
    Route::get('/admin/templates/edit/{id}', 'Admin\TemplatesController@edit');

    Route::get('/admin/users', 'Admin\UsersController@index');
    Route::get('/admin/users/create', 'Admin\UsersController@create');
    Route::get('/admin/users/edit/{id}', 'Admin\UsersController@edit');

    Route::get('/home', 'HomeController@index')->name('home');
});

Auth::routes();

Route::any('/{any}', 'CmsController@route')->where('any', '.*');

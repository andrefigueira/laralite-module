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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', 'Admin\AdminController@home');
Route::get('/admin/home', 'Admin\AdminController@home');

Route::get('/admin/pages', 'Admin\PagesController@index');
Route::get('/admin/pages/create', 'Admin\PagesController@create');
Route::get('/admin/pages/edit/{id}', 'Admin\PagesController@edit');

Route::get('/admin/users', 'Admin\UsersController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

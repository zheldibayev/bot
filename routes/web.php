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

Route::get('/category', 'CategoryController@create');
Route::post('/category/create', 'CategoryController@store');
Route::get('/product', 'ProductController@index');
Route::post('/product/create', 'ProductController@store');
Route::get('/news', 'NewsController@index');
Route::post('/news/create', 'NewsController@store');


Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');

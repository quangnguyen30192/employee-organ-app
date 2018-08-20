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

Route::get('/', ['as' => 'index', 'uses' => 'EmployeeController@index']);
Route::get('/upload', ['as' => 'upload.get', 'uses' => 'EmployeeController@index']);
Route::post('/upload', ['as' => 'upload', 'uses' => 'EmployeeController@upload']);

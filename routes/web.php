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

use Illuminate\Support\Facades\Route;

Route::get('/', 'ResponseController@index');
Route::get('/game', 'ResponseController@init');
Route::get('/bezier', 'ResponseController@bezier');

//Route::any('/ajax/open', 'AjaxController@open');
//Route::get('/debug', 'AjaxController@debug');
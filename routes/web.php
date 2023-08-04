<?php

use Illuminate\Support\Facades\Route;

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

Route::view('/', 'welcome');

Route::view('/words', 'words')->name('words-page');
Route::view('/groups', 'groups')->name('groups-page');
Route::view('/sentences', 'sentence-index')->name('sentence-index-page');
Route::view('/sentences/add', 'sentence-add')->name('sentence-add-page');

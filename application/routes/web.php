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

Route::view('/', 'welcome');

Route::get('agbs/{agb}/download', 'AgbController@download')
    ->name('agbs.download')
    ->middleware('signed');

Route::middleware(['auth'])->group(function () {
    Route::get('/documents/{document}/download', 'UserDocumentController@download')
        ->name('documents.download')
        ->middleware('signed');

    Route::resource('agbs', 'AgbController');
    Route::resource('documents', 'UserDocumentController');
    Route::resource('users', 'UserController');

    Route::get('/home', 'HomeController@index')->name('home');
});

Auth::routes();

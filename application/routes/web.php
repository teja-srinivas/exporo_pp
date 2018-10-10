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

Route::prefix('agbs')->group(function () {
    Route::get('{agb}/download', 'AgbController@download')
        ->name('agbs.download')
        ->middleware('signed');

    Route::get('latest/{type}', 'AgbController@latest')->name('agbs.latest');
});

Route::middleware(['auth', 'accepted'])->group(function () {
    Route::get('/documents/{document}/download', 'UserDocumentController@download')
        ->name('documents.download')
        ->middleware('signed');

    Route::resource('agbs', 'AgbController');
    Route::get('bills/preview/{user}', 'BillController@preview');
    Route::resource('bills/commissions/types', 'CommissionTypeController')->names('commissionTypes');
    Route::resource('bills/commissions', 'CommissionController');
    Route::resource('bills', 'BillController');
    Route::resource('documents', 'UserDocumentController');
    Route::resource('users', 'UserController');
    Route::resource('projects', 'ProjectController', ['only' => ['index', 'show', 'update']]);
    Route::resource('roles', 'RoleController', ['except' => ['index']]);
    Route::resource('schemas', 'SchemaController');
    Route::resource('investments', 'InvestmentController', ['only' => ['index']]);

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/authorization', 'AuthorizationController@index')->name('authorization.index');
});

Auth::routes();

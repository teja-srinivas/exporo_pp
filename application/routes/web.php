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

Route::middleware(['auth', 'accepted', 'filled'])->group(function () {
    Route::get('/documents/{document}/download', 'UserDocumentController@download')
        ->name('documents.download')
        ->middleware('signed');

    Route::resource('agbs', 'AgbController');
    Route::get('bills/preview/{user}', 'BillController@preview');
    Route::resource('bills', 'BillController');
    Route::resource('commissions/bundles', 'BonusBundleController')->names('commissions.bundles');
    Route::resource('commissions/types', 'CommissionTypeController')->names('commissions.types');
    Route::resource('commissions', 'CommissionController', ['only' => ['index']]);
    Route::resource('projects', 'ProjectController', ['only' => ['index', 'show', 'update']]);
    Route::resource('roles', 'RoleController', ['except' => ['index']]);
    Route::resource('schemas', 'SchemaController');
    Route::resource('users/documents', 'UserDocumentController');
    Route::resource('users', 'UserController');

    Route::prefix('users')->name('users.')->group(function () {
        Route::resource('{user}/investments', 'User\InvestmentController', ['only' => ['index']]);
        Route::resource('{user}/investors', 'User\InvestorController', ['only' => ['index']]);
        Route::resource('{user}/commission-bonuses', 'User\CommissionBonusController', ['only' => [
            'store', 'update', 'destroy'
        ]]);
    });

    Route::get('/commission-details', 'User\CommissionDetails')->name('commission-details');

    Route::get('/home', 'HomeController@index')->name('home');

    Route::view('/affiliate/links', 'affiliate/links')->name('affiliate.links');
    Route::view('/affiliate/mails', 'affiliate/mails')->name('affiliate.mails');

    Route::get('/authorization', 'AuthorizationController@index')->name('authorization.index');
});

Route::get('user/{user}/doi', 'UserController@doi')->name('doi')->middleware('signed');
Route::get('bills/pdf/{bill}', 'BillController@billPdf')->middleware('auth.basic.once');

Auth::routes();

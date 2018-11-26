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

Route::redirect('/', 'https://p.exporo.de/');

Route::prefix('agbs')->group(function () {
    Route::get('{agb}/download', 'AgbController@download')
        ->name('agbs.download')
        ->middleware('signed');

    Route::get('latest/{type}', 'AgbController@latest')->name('agbs.latest');
});

Route::middleware(['verified'])->group(function () {
    Route::resource('users/{user}/bundle-selection', 'User\BundleSelection', ['only' => ['index', 'store']])
        ->names('users.bundle-selection');

    Route::middleware(['bundle-selected', 'accepted', 'filled'])->group(function () {
        Route::get('authorization', 'AuthorizationController')->name('authorization.index');
        Route::resource('agbs', 'AgbController');
        Route::get('bills/preview/{user}', 'BillController@preview')->name('bills.preview');
        Route::resource('bills', 'BillController');
        Route::resource('commissions/bundles', 'BonusBundleController')->names('commissions.bundles');
        Route::resource('commissions/types', 'CommissionTypeController')->names('commissions.types');
        Route::resource('commissions', 'CommissionController', ['only' => ['index']]);
        Route::resource('projects', 'ProjectController', ['only' => ['index', 'show', 'update']]);
        Route::resource('roles', 'RoleController', ['except' => ['index']]);
        Route::resource('schemas', 'SchemaController');
        Route::resource('users/documents', 'UserDocumentController'); // TODO move this under the user namespace
        Route::resource('users', 'UserController');

        Route::get('documents/{document}/download', 'UserDocumentController@download')
            ->name('documents.download')
            ->middleware('signed');

        Route::prefix('users/{user}')->name('users.')->namespace('User')->group(function () {
            Route::resource('investments', 'InvestmentController', ['only' => ['index']]);
            Route::resource('investors', 'InvestorController', ['only' => ['index']]);
            Route::resource('commission-bonuses', 'CommissionBonusController', ['only' => [
                'store', 'update', 'destroy'
            ]]);
        });

        Route::get('home', 'HomeController')->name('home');
        Route::get('commission-details', 'User\CommissionDetails')->name('commission-details');
        Route::view('affiliate/links', 'affiliate/links')->name('affiliate.links');
        Route::view('affiliate/mails', 'affiliate/mails')->name('affiliate.mails');
    });
});

Route::get('bills/{bill}/pdf', 'BillController@billPdf')->middleware('auth.basic.once')->name('pdf.creation');

Auth::routes(['verify' => true]);

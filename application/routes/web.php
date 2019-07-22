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

Route::middleware(['referred'])->group(function () {
    Route::redirect('/', 'https://p.exporo.de/');
    Auth::routes(['verify' => true]);
});

Route::prefix('agbs')->group(function () {
    Route::get('{agb}/download', 'AgbController@download')
        ->name('agbs.download')
        ->middleware('signed');

    Route::get('latest/{type?}', 'AgbController@latest')->name('agbs.latest');
});

Route::middleware(['verified'])->group(function () {
    Route::get('{contract}/confirm', 'ConfirmationController@show')->name('contracts.confirm');

    Route::middleware(['active-contract', 'filled'])->group(function () {
        Route::get('authorization', 'AuthorizationController')->name('authorization.index');
        Route::get('bills/export', 'ExportBillsController')->name('bills.export');
        Route::get('bills/preview/{user}', 'BillController@preview')->name('bills.preview');
        Route::resource('agbs', 'AgbController');
        Route::resource('banner-sets', 'BannerSetController')->names('banners.sets')->parameter('banner-sets', 'set');
        Route::resource('banners', 'BannerController')->only('store', 'destroy');
        Route::resource('bills', 'BillController');
        Route::resource('commissions/bundles', 'BonusBundleController')->names('commissions.bundles')->except('show');
        Route::resource('commissions/types', 'CommissionTypeController')->names('commissions.types');
        Route::resource('commissions', 'CommissionController')->only('index');
        Route::resource('projects', 'ProjectController')->only('index', 'show', 'update');
        Route::resource('roles', 'RoleController')->except('index');
        Route::resource('schemas', 'SchemaController');
        Route::resource('documents', 'DocumentController');
        Route::resource('users', 'UserController');

        Route::prefix('contracts')->namespace('Contract')->group(function () {
            Route::resource('/', 'ContractController')->only('show', 'edit', 'update')->names('contracts')->parameter('', 'contract');
            Route::put('{contract}/status', 'ContractStatusController@update')->name('contract-status.update');
            Route::post('{contract}/confirm', 'ConfirmationController@store');
        });

        Route::prefix('users/{user}')->name('users.')->namespace('User')->group(function () {
            Route::resource('documents', 'DocumentController')->only('index');
            Route::resource('investments', 'InvestmentController')->only('index');
            Route::resource('investors', 'InvestorController')->only('index');
            Route::resource('users', 'UserController')->only('index');
            Route::resource('commission-bonuses', 'CommissionBonusController')->only('store', 'update', 'destroy');
        });

        Route::get('home', 'HomeController')->name('home');
        Route::get('commission-details', 'User\CommissionDetails')->name('commission-details');

        Route::get('affiliate/banners', 'BannerController@index')->name('affiliate.banners.index');
        Route::view('affiliate/child-users', 'affiliate/child-users')->name('affiliate.child-users');
        Route::resource('affiliate/links', 'LinkController')->except('show')->names('affiliate.links');
        Route::resource('affiliate/mails', 'MailingController')->names('affiliate.mails');
    });
});

Route::get('bills/{bill}/pdf', 'BillController@billPdf')->middleware('signed')->name('bills.pdf');
Route::get('users/{user}/login', 'UserController@loginUsingId')->middleware('signed')->name('users.login');

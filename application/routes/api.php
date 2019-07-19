<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->name('api.')->namespace('Api')->group(function () {
    Route::apiResource(
        'commissions/bonuses',
        'CommissionBonusController',
        ['except' => ['index']]
    )->names('commissions.bonuses');

    Route::apiResource('commissions', 'CommissionController');

    Route::put(
        'commissions',
        'CommissionController@updateMultiple'
    )->name('commissions.updateBatch');

    Route::get('users/details', 'UserDetailsController@index')->name('users.details.index');

    Route::post('actions/decrypt', 'Actions\DecryptController');
    Route::post('actions/encrypt', 'Actions\EncryptController');
});

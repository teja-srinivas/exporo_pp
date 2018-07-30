<?php

use Illuminate\Http\Request;

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
    Route::apiResource('commissions', 'CommissionController');
    Route::put('commissions', 'CommissionController@updateMultiple')->name('commissions.updateBatch');
});

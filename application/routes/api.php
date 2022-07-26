<?php

declare(strict_types=1);

use App\Http\Controllers\Api;
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

Route::name('api.')->middleware('auth:api')->group(static function () {
    Route::apiResource(
        'commissions/bonuses',
        Api\CommissionBonusController::class,
        ['except' => ['index']]
    )->names('commissions.bonuses');

    Route::apiResource('commissions', Api\CommissionController::class);
    Route::get('pending', [Api\CommissionController::class, 'pending'])->name('pending');
    Route::delete('commissions', [Api\CommissionController::class, 'destroyMultiple']);
    Route::apiResource('contracts/templates', Api\ContractTemplateController::class)->names('contracts.templates');
    Route::apiResource('contracts/templates/{template}/bonuses', Api\ContractTemplateBonusController::class)
        ->names('contracts.templates.bonuses')
        ->only('store');

    Route::apiResource('contracts/{contract}/bonuses', Api\ProductContractBonusController::class)
        ->names('contracts.bonuses')
        ->only('store');

    Route::put(
        'commissions',
        [Api\CommissionController::class, 'updateMultiple']
    )->name('commissions.updateBatch');

    Route::get('users/details', [Api\UserDetailsController::class, 'index'])->name('users.details.index');

    Route::post('actions/decrypt', Api\Actions\DecryptController::class);
    Route::post('actions/encrypt', Api\Actions\EncryptController::class);

    Route::get('dashboard/investments', [Api\DashboardController::class, 'getInvestments'])
        ->name('dashboard.investments');
    Route::get('dashboard/commissions', [Api\DashboardController::class, 'getCommissions'])
        ->name('dashboard.commissions');

    Route::apiResource('campaigns', Api\CampaignController::class)
        ->only('store', 'update')
        ->names('campaigns');
    Route::post('campaigns/file', [Api\CampaignController::class, 'deleteFile'])
        ->name('campaigns.file.delete');

    Route::get('affiliate-dashboard', [Api\AffiliateDashboardController::class, 'getClicks'])
        ->name('affiliate-dashboard');

    Route::put(
        'projects/multiple',
        [Api\ProjectController::class, 'updateMultiple']
    )->name('projects.updateMultiple');
    Route::put('project/{project}', [Api\ProjectController::class, 'update'])
        ->name('project.update');
});

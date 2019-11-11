<?php

declare(strict_types=1);

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

use App\Http\Controllers as C;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::domain(config('app.shortener_url'))->group(static function () {
    Route::get('{link}', [C\LinkInstanceController::class, 'show'])
        ->name('short-link');
});

Route::middleware(['referred'])->group(static function () {
    Route::redirect('/', 'https://p.exporo.de/');

    Route::namespace('App\Http\Controllers')->group(static function () {
        Auth::routes(['verify' => true]);
    });
});

Route::prefix('agbs')->group(static function () {
    Route::get('{agb}/download', [C\AgbController::class, 'download'])
        ->name('agbs.download')
        ->middleware('signed');

    Route::get('latest/{type?}', [C\AgbController::class, 'latest'])
        ->name('agbs.latest');
});

Route::middleware(['verified'])->group(static function () {
    Route::middleware(['accepted', 'filled'])->group(static function () {
        Route::get('authorization', C\AuthorizationController::class)
            ->name('authorization.index');
        Route::get('bills/export', C\ExportBillsController::class)
            ->name('bills.export');
        Route::get('bills/preview/{user}', [C\BillController::class, 'preview'])
            ->name('bills.preview');
        Route::resource('agbs', C\AgbController::class);
        Route::resource('banner-sets', C\BannerSetController::class)
            ->parameter('banner-sets', 'set')
            ->names('banners.sets');
        Route::resource('banners', C\BannerController::class)
            ->only('store', 'destroy');
        Route::resource('bills', C\BillController::class);
        Route::resource('commissions/types', C\CommissionTypeController::class)
            ->names('commissions.types');
        Route::resource('commissions', C\CommissionController::class)
            ->only('index');
        Route::resource('projects', C\ProjectController::class)
            ->only('index', 'show', 'update');
        Route::resource('roles', C\RoleController::class)
            ->except('index');
        Route::resource('schemas', C\SchemaController::class);
        Route::resource('documents', C\DocumentController::class);
        Route::resource('users', C\UserController::class);

        Route::prefix('contracts')->group(static function () {
            Route::resource('templates', C\Contract\ContractTemplateController::class)
                ->names('contracts.templates');

            Route::resource('/', C\Contract\ContractController::class)
                ->only('show', 'edit', 'update', 'destroy')
                ->parameter('', 'contract')
                ->names('contracts');

            Route::put('{contract}/status', [C\Contract\ContractStatusController::class, 'update'])
                ->name('contract-status.update');
        });

        Route::prefix('users/{user}')->name('users.')->group(static function () {
            Route::resource('contracts', C\User\ContractController::class)
                ->only('store');
            Route::resource('documents', C\User\DocumentController::class)
                ->only('index');
            Route::resource('investments', C\User\InvestmentController::class)
                ->only('index');
            Route::resource('investors', C\User\InvestorController::class)
                ->only('index');
            Route::resource('users', C\User\UserController::class)
                ->only('index');
            Route::resource('commission-bonuses', C\User\CommissionBonusController::class)
                ->only('store', 'update', 'destroy');
            Route::resource('verification', C\User\VerificationController::class)
                ->only('store');
        });

        Route::get('home', C\HomeController::class)
            ->name('home');
        Route::get('commission-details', C\User\CommissionDetails::class)
            ->name('commission-details');
        Route::get('affiliate/embeds', [C\EmbedController::class, 'index'])
            ->name('affiliate.embeds.index');
        Route::get('affiliate/banners', [C\BannerController::class, 'index'])
            ->name('affiliate.banners.index');
        Route::view('affiliate/child-users', 'affiliate/child-users')
            ->name('affiliate.child-users');
        Route::resource('affiliate/links', C\LinkController::class)
            ->except('show')
            ->names('affiliate.links');

        Route::prefix('affiliate/mails')->group(static function () {
            Route::resource('/', C\MailingController::class)
                ->parameter('', 'mail')
                ->names('affiliate.mails');
            Route::get('{mail}/preview', [C\Mailing\PreviewController::class, 'show'])
                ->name('affiliate.mails.preview');
            Route::get('{mail}/download', [C\Mailing\DownloadController::class, 'show'])
                ->name('affiliate.mails.download');
        });
    });
});

Route::get('bills/{bill}/pdf', [C\BillController::class, 'billPdf'])
    ->middleware('signed')
    ->name('bills.pdf');

Route::get('users/{user}/login', [C\UserController::class, 'loginUsingId'])
    ->middleware('signed')
    ->name('users.login');

Route::get('affiliate/embed', [C\EmbedController::class, 'show'])
    ->name('affiliate.embed.show');

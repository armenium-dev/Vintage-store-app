<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WebhooksController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\UploaderController;

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

Route::get('/', [WelcomeController::class, 'index']);
Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('shopify-sync', [ShopifyController::class, 'shopifySync'])->middleware(['auth'])->name('shopifySync');
Route::get('webhooks-create', [WebhooksController::class, 'createWebhooks'])->middleware(['auth'])->name('createWebhooks');
Route::get('webhooks-update', [WebhooksController::class, 'updateWebhooks'])->middleware(['auth'])->name('updateWebhooks');
Route::get('webhooks-list', [WebhooksController::class, 'listWebhooks'])->middleware(['auth'])->name('listWebhooks');
Route::resource('settings', SettingsController::class);
Route::get('links', [LinksController::class, 'index'])->middleware(['auth'])->name('links');
Route::get('upload-csv', [UploaderController::class, 'index'])->middleware(['auth'])->name('uploadCsv');
Route::post('do-upload-csv', [UploaderController::class, 'duUploadCsv'])->middleware(['auth'])->name('doUploadCsv');

# Test routes
Route::get('set-order-products', [ShopifyController::class, 'setOrderProducts'])->middleware(['auth']);


/**
 * https://laravel.com/docs/9.x/authentication
 */
require __DIR__.'/auth.php';

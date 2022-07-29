<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\SyncShopifyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WebhooksController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UploadsController;
use App\Http\Controllers\OrdersController;

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

Route::get('shopify-sync', [SyncShopifyController::class, 'shopifySync'])->middleware(['auth'])->name('shopifySync');

Route::get('webhooks-create', [WebhooksController::class, 'create'])->middleware(['auth'])->name('createWebhooks');
Route::get('webhooks-update', [WebhooksController::class, 'update'])->middleware(['auth'])->name('updateWebhooks');
Route::get('webhooks-list', [WebhooksController::class, 'list'])->middleware(['auth'])->name('listWebhooks');

Route::resource('settings', SettingsController::class);
Route::resource('orders', OrdersController::class);
Route::get('import-order-by-id', [OrdersController::class, 'importOrderByID'])->middleware(['auth'])->name('importOrderByID');
Route::post('store-order-by-id', [OrdersController::class, 'storeOrderByID'])->middleware(['auth'])->name('storeOrderByID');

Route::get('sales-shopify', [SalesController::class, 'salesOnShopify'])->middleware(['auth'])->name('salesOnShopify');
Route::get('sales-depop', [SalesController::class, 'salesOnDepop'])->middleware(['auth'])->name('salesOnDepop');
Route::get('sales-asos', [SalesController::class, 'salesOnAsos'])->middleware(['auth'])->name('salesOnAsos');
Route::post('sales-remove', [SalesController::class, 'remove'])->middleware(['cors'])->name('salesRemove');

Route::get('upload', [UploadsController::class, 'index'])->middleware(['auth'])->name('uploadForm');
Route::get('upload-result', [UploadsController::class, 'result'])->middleware(['auth'])->name('uploadResult');
Route::post('upload-files', [UploadsController::class, 'uploadFiles'])->middleware(['auth'])->name('uploadFiles');



/**
 * https://laravel.com/docs/9.x/authentication
 */
require __DIR__.'/auth.php';

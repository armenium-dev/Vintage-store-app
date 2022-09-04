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
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\CustomProductsController;

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
Route::get('orders/mystery-box', [OrdersController::class, 'mysteryBox'])->middleware(['auth'])->name('mysteryBox');
Route::get('orders/mystery-box-collect/{oid}/line/{lid}/product/{pid}/variant/{vid}', [OrdersController::class, 'mysteryBoxCollectProducts'])->middleware(['auth'])->name('mysteryBoxCollectProducts');
Route::get('orders/mystery-box-collect/{id}', [OrdersController::class, 'mysteryBoxCollect'])->middleware(['auth'])->name('mysteryBoxCollect');
Route::get('import-order-by-id', [OrdersController::class, 'importOrderByID'])->middleware(['auth'])->name('importOrderByID');
Route::post('store-order-by-id', [OrdersController::class, 'storeOrderByID'])->middleware(['auth'])->name('storeOrderByID');
Route::post('store-order-mystery-box', [OrdersController::class, 'storeOrderMysteryBox'])->middleware(['auth'])->name('storeOrderMysteryBox');
Route::resource('orders', OrdersController::class);
Route::resource('custom-products', CustomProductsController::class);
Route::get('create-orders-mystery-boxes', [OrdersController::class, 'createOrdersMysteryBoxes'])->middleware(['auth'])->name('createOrdersMysteryBoxes');

Route::get('resync-data', [SettingsController::class, 'resyncData'])->middleware(['auth'])->name('resyncData');
Route::get('resync-data-full', [SettingsController::class, 'resyncDataFull'])->middleware(['auth'])->name('resyncDataFull');
Route::get('reset-app', [SettingsController::class, 'resetApp'])->middleware(['auth'])->name('resetApp');

Route::get('warehouse-pick', [WarehouseController::class, 'pick'])->middleware(['auth'])->name('warehousePick');
Route::get('warehouse-pack', [WarehouseController::class, 'pack'])->middleware(['auth'])->name('warehousePack');
Route::post('warehouse-pick-product', [WarehouseController::class, 'pickProduct'])->middleware(['cors'])->name('pickProduct');
Route::post('warehouse-pack-product', [WarehouseController::class, 'packProduct'])->middleware(['cors'])->name('packProduct');

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

<?php

use App\Http\Controllers\API\Shopify\WebhooksController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('shop-1-webhook-orders', [WebhooksController::class, 'shop1WebhookOrders'])->name('shop1WebhookOrders');
Route::post('shop-2-webhook-orders', [WebhooksController::class, 'shop2WebhookOrders'])->name('shop2WebhookOrders');
Route::post('shop-3-webhook-orders', [WebhooksController::class, 'shop3WebhookOrders'])->name('shop3WebhookOrders');

Route::post('shop-1-webhook-products-create', [WebhooksController::class, 'shop1WebhookProductCreate'])->name('shop1WebhookProductCreate');
Route::post('shop-2-webhook-products-create', [WebhooksController::class, 'shop2WebhookProductCreate'])->name('shop2WebhookProductCreate');
Route::post('shop-3-webhook-products-create', [WebhooksController::class, 'shop3WebhookProductCreate'])->name('shop3WebhookProductCreate');

Route::post('shop-1-webhook-products-update', [WebhooksController::class, 'shop1WebhookProductUpdate'])->name('shop1WebhookProductUpdate');
Route::post('shop-2-webhook-products-update', [WebhooksController::class, 'shop2WebhookProductUpdate'])->name('shop2WebhookProductUpdate');
Route::post('shop-3-webhook-products-update', [WebhooksController::class, 'shop3WebhookProductUpdate'])->name('shop3WebhookProductUpdate');

Route::post('shop-1-webhook-products-delete', [WebhooksController::class, 'shop1WebhookProductDelete'])->name('shop1WebhookProductDelete');
Route::post('shop-2-webhook-products-delete', [WebhooksController::class, 'shop2WebhookProductDelete'])->name('shop2WebhookProductDelete');
Route::post('shop-3-webhook-products-delete', [WebhooksController::class, 'shop3WebhookProductDelete'])->name('shop3WebhookProductDelete');



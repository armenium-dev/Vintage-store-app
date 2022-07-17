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



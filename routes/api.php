<?php

use App\Http\Controllers\API\Shopify\WebhookController;
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

Route::post('shop1-webhook', [WebhookController::class, 'shop1Webhook'])->name('shop1-webhook');
Route::post('shop2-webhook', [WebhookController::class, 'shop2Webhook'])->name('shop2-webhook');
Route::post('shop3-webhook', [WebhookController::class, 'shop3Webhook'])->name('shop3-webhook');



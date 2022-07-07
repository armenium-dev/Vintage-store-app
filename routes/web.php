<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\SettingsController;

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
Route::get('shopify-sync', [ShopifyController::class, 'shopifySync'])->middleware(['auth'])->name('shopifysync');
Route::get('create-webhooks', [ShopifyController::class, 'createWebhooks'])->middleware(['auth'])->name('createWebhooks');
Route::get('list-webhooks', [ShopifyController::class, 'listWebhooks'])->middleware(['auth'])->name('listWebhooks');
Route::resource('settings', SettingsController::class);


/**
 * https://laravel.com/docs/9.x/authentication
 */
require __DIR__.'/auth.php';

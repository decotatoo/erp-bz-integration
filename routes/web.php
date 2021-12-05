<?php

use Decotatoo\WoocommerceIntegration\Http\Controllers\BinPackerController;
use Decotatoo\WoocommerceIntegration\Http\Controllers\CommerceCategoryController;
use Decotatoo\WoocommerceIntegration\Http\Controllers\SalesOrderController;
use Decotatoo\WoocommerceIntegration\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('woocommerce')->name('woocommerce.')->group(function () {
    // Route::post('webhook', WebhookController::class)->name('webhook');

    Route::post('bin-packer', [BinPackerController::class, 'simulate'])->name('bin-packer.simulate');
});


Route::middleware(['web', 'auth'])->group(function () {
    // Website Management
    Route::prefix('website-management')->name('website-management.')->group(function () {

        // commerce-category
        Route::resource('commerce-category', CommerceCategoryController::class);
    });

    Route::prefix('sales-order')->name('sales-order.')->group(function () {
        Route::get('/online', [SalesOrderController::class, 'index'])->name('online');
    });
});
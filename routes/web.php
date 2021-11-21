<?php

use Decotatoo\WoocommerceIntegration\Http\Controllers\CommerceCategoryController;
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


// Route::prefix('woocommerce')->name('woocommerce.')->group(function () {
//     Route::post('webhook', WebhookController::class)->name('webhook');

//     Route::post('shipping-weight', [WooCommerceShippingWeightController::class, 'shippingWeight'])->name('shipping-weight');
// });


Route::middleware(['web', 'auth'])->group(function () {
    // Website Management
    Route::prefix('website-management')->name('website-management.')->group(function () {

        // commerce-category
        Route::resource('commerce-category', CommerceCategoryController::class);
    });
});
<?php

use Decotatoo\Bz\Http\Controllers\BinController;
use Decotatoo\Bz\Http\Controllers\CommerceCategoryController;
use Decotatoo\Bz\Http\Controllers\SalesOrderController;
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


Route::middleware(['web', 'auth'])->group(function () {
    // Website Management
    Route::prefix('website-management')->name('website-management.')->group(function () {

        // commerce-category
        Route::resource('commerce-category', CommerceCategoryController::class);
    });

    Route::prefix('sales-order')->name('sales-order.')->group(function () {
        // Sales Order Online
        Route::prefix('online')->name('online.')->group(function () {
            Route::get('/', [SalesOrderController::class, 'index'])->name('index');
            Route::post('/list', [SalesOrderController::class, 'list'])->name('list');
            Route::get('/detail-product/{id}', [SalesOrderController::class, 'detailProduct'])->name('detail-product');

            Route::get('/edit-release/{id}', [SalesOrderController::class, 'editRelease'])->name('edit-release');
        });
    });

    
    // Inventory
    Route::prefix('inventory')->name('inventory.')->group(function () {
        //Box Type Setup
        Route::resource('bin', BinController::class)->except(['show']);
    });
});
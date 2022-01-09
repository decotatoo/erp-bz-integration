<?php

use Decotatoo\Bz\Http\Controllers\BinController;
use Decotatoo\Bz\Http\Controllers\BinPackerController;
use Decotatoo\Bz\Http\Controllers\CommerceCatalogController;
use Decotatoo\Bz\Http\Controllers\CommerceCategoryController;
use Decotatoo\Bz\Http\Controllers\SalesOrderController;
use Decotatoo\Bz\Http\Controllers\UnitBoxController;
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
        Route::resource('commerce-catalog', CommerceCatalogController::class)->except(['store', 'destroy']);
        Route::resource('commerce-category', CommerceCategoryController::class);
    });

    Route::prefix('sales-order')->name('sales-order.')->group(function () {
        // Sales Order Online
        Route::prefix('online')->name('online.')->group(function () {
            Route::get('/', [SalesOrderController::class, 'index'])->name('index');
            Route::post('/list', [SalesOrderController::class, 'list'])->name('list');
            Route::get('/detail-product/{bzOrder}', [SalesOrderController::class, 'detailProduct'])->name('detail-product');

            Route::get('/edit-release/{bzOrder}', [SalesOrderController::class, 'editRelease'])->name('edit-release');

            // @TODO:
            Route::get('/detail-release/{bzOrder}', [SalesOrderController::class, 'detailRelease'])->name('detail-release');


            Route::get('/list-product/{bzOrder}', [SalesOrderController::class, 'listProductWithStock'])->name('listproduct');
            Route::get('/list-scan-out/{bzOrder}', [SalesOrderController::class, 'listScanOut'])->name('listscanout');
            Route::post('/release-product/{bzOrder}', [SalesOrderController::class, 'releaseProduct'])->name('releaseproduct');
            Route::post('/delete-last-product/{bzOrder}', [SalesOrderController::class, 'deleteLastProduct'])->name('deletelastproduct');
            Route::post('/update-shipment/{bzOrder}', [SalesOrderController::class, 'updateShipment'])->name('updateshipment');
        });
    });


    // Inventory
    Route::prefix('inventory')->name('inventory.')->group(function () {
        //Box Type Setup
        Route::resource('bin', BinController::class)->except(['show']);
        Route::resource('unit-box', UnitBoxController::class)->except(['show']);
    });

    // Packing Management
    Route::prefix('packing-management')->name('packing-management.')->group(function () {
        Route::get('/packing-simulation/{packingSimulation}', [BinPackerController::class, 'visualiser'])->name('packing-simulation.visualiser');
    });
});

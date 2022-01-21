<?php

use Decotatoo\Bz\Http\Controllers\BinPackerController;
use Decotatoo\Bz\Http\Controllers\WebhookController;
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

Route::prefix('bz')->name('bz.')->group(function () {
    Route::post('bin-packer', [BinPackerController::class, 'simulate'])->name('bin-packer.simulate');
    Route::post('webhook', WebhookController::class)->name('webhook');
});
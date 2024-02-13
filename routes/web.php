<?php

use Illuminate\Support\Facades\Route;
use Netto\Http\Controllers\CurrencyController;

Route::prefix(CMS_LOCATION)->name('admin.')->group(function() {
    Route::middleware(['admin', 'verified'])->group(function() {
        Route::resource('currency', CurrencyController::class)->except(['toggle']);
    });
});

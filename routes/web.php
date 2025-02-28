<?php

use Illuminate\Support\Facades\Route;
use Netto\Http\Controllers\CurrencyController;

Route::prefix(config('cms.location', 'admin'))->name('admin.')->group(function() {
    Route::middleware(['admin', 'verified'])->group(function() {
        Route::resource('currency', CurrencyController::class)->except(['toggle']);
    });
});

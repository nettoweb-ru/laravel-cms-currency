<?php

use Illuminate\Support\Facades\Route;
use Netto\Http\Controllers\Admin\CurrencyController;

Route::prefix(config('cms.admin-location'))->name('admin.')->middleware(['admin', 'verified', 'permission:admin-currencies'])->group(function() {
    Route::resource('currency', CurrencyController::class)->except(['toggle']);
});

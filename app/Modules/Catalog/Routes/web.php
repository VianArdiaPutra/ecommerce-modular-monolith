<?php

use App\Modules\Catalog\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/produk', [ProductController::class, 'index'])->name('catalog.index');
    Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('catalog.show');
});

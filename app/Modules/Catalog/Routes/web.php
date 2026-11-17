<?php

use App\Modules\Catalog\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Modules\Catalog\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Modules\Catalog\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/produk', [ProductController::class, 'index'])->name('catalog.index');
    Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('catalog.show');
});

// Area admin (RBAC): hanya role admin.
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['web', 'auth:sanctum', 'role:admin'])
    ->group(function () {
        Route::resource('produk', AdminProductController::class)
            ->parameters(['produk' => 'product'])
            ->except('show')
            ->names('products');

        Route::resource('kategori', AdminCategoryController::class)
            ->parameters(['kategori' => 'category'])
            ->except('show')
            ->names('categories');
    });

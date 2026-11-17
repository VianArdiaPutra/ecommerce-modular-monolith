<?php

use App\Modules\Customer\Http\Controllers\AddressController;
use App\Modules\Customer\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.store');
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/akun', fn () => view('customer::account'))->name('account');

        Route::middleware('role:customer')->group(function () {
            Route::get('/akun/alamat/baru', [AddressController::class, 'create'])->name('address.create');
            Route::post('/akun/alamat', [AddressController::class, 'store'])->name('address.store');
        });

        Route::middleware('role:admin')->group(function () {
            Route::get('/admin', fn () => view('customer::admin'))->name('admin.dashboard');
        });
    });
});

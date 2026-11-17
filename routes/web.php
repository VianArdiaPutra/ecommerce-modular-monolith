<?php

use App\Modules\Customer\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    // Hanya untuk tamu (belum login)
    Route::middleware('guest')->group(function () {
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.store');
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.store');
    });

    // Harus login (Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/akun', fn() => view('customer::account'))->name('account');

        // Khusus admin (RBAC)
        Route::middleware('role:admin')->group(function () {
            Route::get('/admin', fn() => view('customer::admin'))->name('admin.dashboard');
        });
    });
});

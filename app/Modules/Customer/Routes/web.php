<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/customer/ping', fn () => 'Customer module OK');
});
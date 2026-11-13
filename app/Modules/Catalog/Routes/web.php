<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/catalog/ping', fn () => 'Catalog module OK');
});
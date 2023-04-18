<?php

use Biin2013\Tiger\Admin\Http\Controllers\Foundations\EmptyController;
use Illuminate\Support\Facades\Route;

Route::middleware('error-scope:admin')->group(function () {
    Route::post('/login', config('tiger.admin.login.controller_class'))->name('login.login');
    Route::put('/login/password', EmptyController::class)->name('login.password');
});
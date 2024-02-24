<?php

use App\Http\Controllers\EmdMobileAPIController;
use App\Http\Controllers\EmdMobilePaymentRenewalController;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile')->middleware('emd_mobile_api_key')->group(function () {
    Route::controller(EmdMobileAPIController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('user-info', 'user_info');
    });
    Route::controller(EmdMobilePaymentRenewalController::class)->group(function () {
        Route::post('new-payment', 'mobile_payment');
    });
});

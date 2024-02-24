<?php

use App\Http\Controllers\EmdCustomPageDisplayController;
use Illuminate\Support\Facades\Route;

Route::controller(EmdCustomPageDisplayController::class)->group(function () {
    Route::get('testing', 'custom_page_display')->name('EmdCustomPage.testing');
});

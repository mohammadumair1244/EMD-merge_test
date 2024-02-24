<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('politica-de-privacidad', [FrontendController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('terminos-y-condiciones', [FrontendController::class, 'terms_and_conditions'])->name('terms_and_conditions');
Route::get('acerca', [FrontendController::class, 'about_us'])->name('about_us');
Route::get('contacto', [FrontendController::class, 'contact_us'])->name('contact_us');
Route::post('gettext', [ToolController::class, 'getText'])->name('gettext');
Route::get('seggetions', [FrontendController::class, 'seggetions'])->name('seggetions');
Route::post('parafraseo', [FrontendController::class, 'parafraseo'])->name('parafraseo');
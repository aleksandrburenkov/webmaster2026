<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalPageController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/privacy-policy', [LegalPageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/offer', [LegalPageController::class, 'offer'])->name('offer');

Route::prefix('portfolio')->name('portfolio.')->group(function () {
    Route::get('/', [PortfolioController::class, 'index'])->name('index');
    Route::get('/{slug}', [PortfolioController::class, 'show'])->name('show');
});

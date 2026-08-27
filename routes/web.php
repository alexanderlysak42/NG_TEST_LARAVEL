<?php

use App\Http\Controllers\PageAController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RegistrationController::class, 'showForm'])->name('home');
Route::post('/register', [RegistrationController::class, 'register'])->name('register');

Route::prefix('/p/{token}')
    ->where(['token' => '[a-f0-9]{64}'])
    ->group(function () {
        Route::get('/', [PageAController::class, 'show'])->name('page-a.show');
        Route::post('/regenerate', [PageAController::class, 'regenerate'])->name('page-a.regenerate');
        Route::post('/deactivate', [PageAController::class, 'deactivate'])->name('page-a.deactivate');
        Route::post('/play', [PageAController::class, 'play'])->name('page-a.play');
        Route::get('/history', [PageAController::class, 'history'])->name('page-a.history');
    });

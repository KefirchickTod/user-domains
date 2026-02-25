<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Domains\DomainController;
use Illuminate\Support\Facades\Route;

// Гостьові маршрути
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Приватні маршрути
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', fn () => redirect()->route('domains.index'));

    // Статичні маршрути перед resource, щоб не конфліктувати з {domain}
    Route::post('domains/check-all', [DomainController::class, 'checkAll'])
        ->name('domains.check-all');

    Route::resource('domains', DomainController::class);

    Route::put('domains/{domain}/settings', [DomainController::class, 'updateSettings'])
        ->name('domains.settings.update');

    Route::post('domains/{domain}/check', [DomainController::class, 'checkNow'])
        ->name('domains.check-now');
});

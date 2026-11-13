<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\TaxTypeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/merchants', [MerchantController::class, 'index'])
        ->name('merchants.index');

    Route::get('/merchants/create', [MerchantController::class, 'create'])
        ->name('merchants.create');

    Route::post('/merchants', [MerchantController::class, 'store'])
        ->name('merchants.store');

    Route::get('/merchants/{merchant}/edit', [MerchantController::class, 'edit'])
        ->name('merchants.edit');

    Route::put('/merchants/{merchant}', [MerchantController::class, 'update'])
        ->name('merchants.update');
});

// Autentikasi pengguna.
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get(
        '/tax-types',
        [TaxTypeController::class, 'index']
    )->name('tax-types.index');

    Route::get(
        '/tax-types/create',
        [TaxTypeController::class, 'create']
    )->name('tax-types.create');

    Route::post(
        '/tax-types',
        [TaxTypeController::class, 'store']
    )->name('tax-types.store');

    Route::get(
        '/tax-types/{taxType}/edit',
        [TaxTypeController::class, 'edit']
    )->name('tax-types.edit');

    Route::put(
        '/tax-types/{taxType}',
        [TaxTypeController::class, 'update']
    )->name('tax-types.update');
});


Route::middleware('auth')->group(function () {
    Route::get(
        '/transactions',
        [TransactionController::class, 'index']
    )->name('transactions.index');

    Route::get(
        '/transactions/create',
        [TransactionController::class, 'create']
    )->name('transactions.create');

    Route::post(
        '/transactions',
        [TransactionController::class, 'store']
    )->name('transactions.store');

    Route::get(
        '/transactions/{transaction}/edit',
        [TransactionController::class, 'edit']
    )->name('transactions.edit');

    Route::put(
        '/transactions/{transaction}',
        [TransactionController::class, 'update']
    )->name('transactions.update');
});

Route::middleware('auth')->group(function () {
    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');
});
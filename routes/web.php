<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('payments', [PaymentController::class, 'index'])->name('payments');
});

Route::prefix('users')->middleware('auth')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::get('{id}/contributions', [ContributionController::class, 'userContributions']);
    Route::get('{id}/payments', [PaymentController::class, 'userPayments']);

    Route::post('/contributions', [ContributionController::class, 'store']);
    Route::post('/penalties', [PenaltyController::class, 'store']);
    Route::post('/investments', [InvestmentController::class, 'store']);
    Route::post('/votes', [VoteController::class, 'store']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::post('/admin/logs', [AdminLogController::class, 'store']);
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

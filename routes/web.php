<?php

use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('payments', [PaymentController::class, 'index'])->name('payments');
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::post('expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::post('expenses/update', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::post('expenses/status/update', [ExpenseController::class, 'updateStatus'])->name('expenses.status.update');

    // Excel
    Route::get('expenses/template/download', [ExpenseController::class, 'downloadTemplate'])->name('expenses.template.download');
    Route::post('expenses/upload', [ExpenseController::class, 'upload'])->name('expenses.upload');

    // Investments
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');

    // Events Management
    Route::get('/events', function () {
        return inertia()->render('Events/Index');
    })->name('events');

    // Members Management
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::post('/members/store', [MemberController::class, 'store'])->name('members.store');

    // Groups Management
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/groups/store', [GroupController::class, 'store'])->name('groups.store');

    Route::get('/groups/all', [GroupController::class, 'all'])->name('groups.all');

    // Group Member Management
    Route::post('/member/group/add', [GroupController::class, 'addMemberToGroup'])->name('member.group.add');

    // Family Management
    Route::group(['prefix' => '/families', 'as' => 'families.'], function () {
        Route::get('/', [FamilyController::class, 'index'])->name('index');

        Route::post('/store', [FamilyController::class, 'store'])->name('store');

        Route::post('/member/add', [FamilyController::class, 'addMemberToFamily'])->name('member.add');
    });

    // Logs
    Route::get('/logs', function () {
        return inertia()->render('Logs/Index');
    });
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

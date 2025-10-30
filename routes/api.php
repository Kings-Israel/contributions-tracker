<?php

use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payment/confirmation', function (Request $request) {
    info($request->all());
});

Route::get('/payment/validation', function (Request $request) {
    info($request->all());
});

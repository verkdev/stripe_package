<?php

use Illuminate\Support\Facades\Route;
use Mohsin\StripeKit\Http\Controllers\StripeController;

Route::get('/stripe', [StripeController::class, 'index']);
Route::post('/stripe/checkout', [StripeController::class, 'checkout']);
Route::get('/stripe/success', [StripeController::class, 'success']);
Route::get('/stripe/cancel', [StripeController::class, 'cancel']);

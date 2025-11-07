<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\PaymentController;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Webhook (public)
Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook']);

// Routes محمية (token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/create-checkout-session', [StripeController::class, 'createCheckoutSession']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payment/confirm', [PaymentController::class, 'confirmPayment']);

});

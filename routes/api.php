<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    Route::get('/enquiries', [EnquiryController::class, 'index']);
    Route::put('/enquiries/{id}/assign', [EnquiryController::class, 'assign']);
    Route::patch('/enquiries/{id}/status', [EnquiryController::class, 'updateStatus']);
    
    Route::apiResource('itineraries', ItineraryController::class)->except(['create', 'edit']);
    
    Route::get('/quotations', [QuotationController::class, 'index']);
    Route::post('/quotations', [QuotationController::class, 'store']);
    Route::get('/quotations/{id}', [QuotationController::class, 'show']);
    
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
});

// Public routes
Route::post('/enquiries', [EnquiryController::class, 'store']);
Route::get('/quotations/public/{uniqueId}', [QuotationController::class, 'publicShow']);

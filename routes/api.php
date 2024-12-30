<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AgentController;

Route::prefix('booking')->group(function () {
    Route::get('freeTimes/{date}', [BookingController::class, 'getFreeTimes']);
    Route::post('', [BookingController::class, 'createBooking']);
});

Route::prefix('client')->group(function () {
    Route::get('/{id}', [ClientController::class, 'getClientById']);
    Route::post('', [ClientController::class, 'createClient']);
});

Route::prefix('agent')->group(function () {
    Route::get('/{id}', [AgentController::class, 'getAgentById']);
    Route::post('', [AgentController::class, 'createAgent']);
});
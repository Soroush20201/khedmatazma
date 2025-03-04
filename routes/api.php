<?php

use App\Http\Controllers\API\PenaltyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\EditionController;
use App\Http\Controllers\API\ReservationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::apiResource('books', BookController::class);
Route::apiResource('editions', EditionController::class);
Route::apiResource('reservations', ReservationController::class);
Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);
Route::post('reservations/{reservation}/return', [ReservationController::class, 'returnBook']);
Route::get('penalties', [PenaltyController::class, 'index']);
Route::post('penalties/apply', [PenaltyController::class, 'checkAndApplyPenalties']);

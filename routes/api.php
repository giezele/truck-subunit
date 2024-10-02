<?php

use App\Http\Controllers\TruckController;
use App\Http\Controllers\TruckSubunitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('trucks', TruckController::class);

Route::post('/truck-subunits', [TruckSubunitController::class, 'store']);



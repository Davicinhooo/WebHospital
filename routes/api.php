<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\PatientsController;
use App\Http\Controllers\Api\DoctorsController;
use App\Http\Controllers\Api\QuotesController;
use App\Http\Controllers\Api\DiagnosticsController;
use App\Http\Controllers\Api\MedicationsController;
use App\Http\Controllers\Api\TreatmentsController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('patients', App\Http\Controllers\Api\PatientsController::class);
Route::apiResource('doctors', App\Http\Controllers\Api\DoctorsController::class);
Route::apiResource('quotes', App\Http\Controllers\Api\QuotesController::class);
Route::apiResource("diagnostics", App\Http\Controllers\Api\DiagnosticsController::class);
Route::apiResource("medications", App\Http\Controllers\Api\MedicationsController::class);
Route::apiResource("treatments", App\Http\Controllers\Api\TreatmentsController::class);
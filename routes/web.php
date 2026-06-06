<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\SocialController;
use App\Http\Controllers\Api\DoctorsController;
use App\Http\Controllers\Api\QuotesController;
use App\Http\Controllers\Api\DiagnosticsController;
use App\Http\Controllers\Api\TreatmentsController;
use App\Http\Controllers\Api\MedicationsController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/auth/github', [App\Http\Controllers\Api\SocialController::class, 'redirectToGithub'])->name('github.redirect');
Route::get('/auth/github/callback', [App\Http\Controllers\Api\SocialController::class, 'handleGithubCallback'])->name('github.callback');
Route::get('/auth/google', [App\Http\Controllers\Api\SocialController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [App\Http\Controllers\Api\SocialController::class, 'handleGoogleCallback'])->name('google.callback');   


Route::get('/medicos/generar-licencia', [DoctorsController::class, 'generarLicencia'])->name('medicos.generarLicencia')->middleware('auth');

// RUTAS DE LOS GESTORES
Route::resource('pacientes', PatientsController::class)->middleware('auth');
Route::resource("medicos", DoctorsController::class)->middleware('auth');
Route::resource("citas", QuotesController::class)->middleware('auth');
Route::resource("diagnosticos", DiagnosticsController::class)->middleware('auth');
Route::resource("tratamientos",TreatmentsController::class)->middleware('auth');
Route::resource("medicaciones", MedicationsController::class)->middleware('auth');
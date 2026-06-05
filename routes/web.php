<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\SocialController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/auth/github', [App\Http\Controllers\Api\SocialController::class, 'redirectToGithub'])->name('github.redirect');
Route::get('/auth/github/callback', [App\Http\Controllers\Api\SocialController::class, 'handleGithubCallback'])->name('github.callback');
Route::get('/auth/google', [App\Http\Controllers\Api\SocialController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [App\Http\Controllers\Api\SocialController::class, 'handleGoogleCallback'])->name('google.callback');   

// RUTAS DE LOS GESTORES

Route::resource('pacientes', PatientsController::class)->middleware('auth');
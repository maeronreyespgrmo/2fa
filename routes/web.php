<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/2fa-setup', [App\Http\Controllers\Google2FAController::class, 'setup']);
Route::post('/2fa-setup', [App\Http\Controllers\Google2FAController::class, 'store']);
Route::post('/otp', [App\Http\Controllers\Google2FAController::class, 'verify']);
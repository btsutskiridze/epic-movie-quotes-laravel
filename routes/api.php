<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
	Route::post('register', 'register')->name('user.register');
	Route::post('login', 'login')->name('user.register');
	Route::post('auto-login', 'autoLogin')->name('user.autoLogin');
});
Route::post('verification', [VerificationController::class, 'verifyEmail'])->name('verification.verifyEmail');

Route::controller(GoogleController::class)->middleware(['web'])->group(function () {
	Route::get('redirect', 'redirectToGoogle')->name('google.redirect');
	Route::get('callback', 'handleGoogleCallback')->name('google.callback');
});

Route::controller(ResetPasswordController::class)->group(function () {
	Route::post('forget-password', 'sentEmail')->name('forget.password');
	Route::post('reset-password', 'updatePassword')->name('reset.password');
});
<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\ResetPasswordController;
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
	Route::post('login', 'login')->middleware('email.verified')->name('user.login');
	Route::get('logout', 'logout')->middleware('jwt.auth')->name('user.logout');
	Route::post('auto-login', 'autoLogin')->name('user.auto-login');
	Route::get('me', 'me')->middleware('jwt.auth')->name('me');
});
Route::post('verification', [VerificationController::class, 'verifyEmail'])->name('verification.verify-email');

Route::controller(GoogleController::class)->group(function () {
	Route::get('redirect', 'redirectToGoogle')->name('google.redirect');
	Route::get('callback', 'handleGoogleCallback')->name('google.callback');
});

Route::controller(ResetPasswordController::class)->group(function () {
	Route::post('forget-password', 'sentEmail')->name('forget.password');
	Route::post('reset-password', 'updatePassword')->name('reset.password');
});

Route::controller(MovieController::class)->group(function () {
	Route::get('movies', 'index')->name('movies.index');
	Route::post('movie/store', 'store')->name('movie.store');
	Route::get('movies/{movie:id}', 'get')->name('movies.get');
	Route::post('movies/{movie:id}/update', 'update')->name('movies.update');
	Route::delete('/movies/{movie:id}/destroy', 'destroy')->name('movie.destroy');
});
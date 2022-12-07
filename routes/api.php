<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
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

Route::middleware(['jwt.auth'])->group(function () {
	Route::controller(UserController::class)->group(function () {
		Route::post('user/update', 'update')->name('user.update');
	});

	Route::controller(MovieController::class)->group(function () {
		Route::get('movies', 'index')->name('movies.index');
		Route::post('movie/store', 'store')->name('movie.store');
		Route::get('movies/{movie:id}', 'get')->name('movies.get');
		Route::post('movies/{movie:id}/update', 'update')->name('movies.update');
		Route::delete('movies/{movie:id}', 'destroy')->name('movie.destroy');
	});

	Route::controller(QuoteController::class)->group(function () {
		Route::get('quotes', 'index')->name('quotes.index');
		Route::post('quotes/search', 'search')->name('quotes.search');
		Route::post('number-quotes', 'numberQuotes')->name('quotes.number');
		Route::post('quote/store', 'store')->name('quote.store');
		Route::get('quotes/{quote:id}', 'get')->name('quote.get');
		Route::get('quotes/{quote:id}/with-relations', 'getWithRelations')->name('quote.with-relations');
		Route::post('quotes/{quote:id}/update', 'update')->name('quotes.update');
		Route::delete('quotes/{quote:id}', 'destroy')->name('quote.destroy');
	});

	Route::controller(CommentController::class)->group(function () {
		Route::post('quotes/{quote:id}/comment', 'store')->name('comment.store');
	});

	Route::controller(LikesController::class)->group(function () {
		Route::post('quotes/{quote:id}/likable', 'likable')->name('quote.likable');
		Route::post('quotes/{quote:id}/like', 'like')->name('quote.like');
	});

	Route::controller(NotificationController::class)->group(function () {
		Route::get('notifications', 'index')->name('notifications.index');
		Route::get('notifications/read-all', 'read')->name('notifications.read');
	});

	Route::controller(EmailController::class)->group(function () {
		Route::post('emails/store', 'store')->name('email.store');
		Route::post('email/verification', 'verification')->name('email.verification');
		Route::delete('email/{email:id}', 'delete')->name('email.delete');
		Route::post('emails/{email:id}/make-primary', 'makePrimary')->name('email.make-primary');
	});
});

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
	Route::post('forget-password', 'sentEmail')->name('password.forget');
	Route::post('reset-password', 'updatePassword')->name('password.reset');
});
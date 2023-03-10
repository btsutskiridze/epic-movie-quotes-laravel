<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
	public function redirectToGoogle(): RedirectResponse
	{
		return Socialite::driver('google')->stateless()->redirect();
	}

	public function handleGoogleCallback(): RedirectResponse
	{
		try
		{
			//create a user using socialite driver google
			$googleUser = Socialite::driver('google')->stateless()->user();
			// if the user exits, use that user and login
			$finduser = User::where('email', $googleUser->email)->get()->first();
			if ($finduser)
			{
				$payload = [
					'exp' => Carbon::now()->addDay()->timestamp,
					'uid' => $finduser->id,
				];

				$jwt = JWT::encode($payload, config('auth.jwt_secret'), config('auth.jwt_algo'));

				$cookie = cookie('access_token', $jwt, 60, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
				return Redirect::to(config('app.front_url'))->withCookie($cookie);
			}
			else
			{
				//user is not yet created, so create first
				$newUser = User::create([
					'google_id'=> $googleUser->getId(),
					'name'     => $googleUser->name,
					'email'    => $googleUser->email,
					'avatar'   => $googleUser->getAvatar(),
					'password' => encrypt(''),
					'token'    => Str::random(60),
				]);

				$payload = [
					'exp' => Carbon::now()->addDay()->timestamp,
					'uid' => $newUser->id,
				];

				$jwt = JWT::encode($payload, config('auth.jwt_secret'), config('auth.jwt_algo'));

				$cookie = cookie('access_token', $jwt, 60, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
				return Redirect::to(config('app.front_url'))->withCookie($cookie);
				// go to the dashboard
			}
			//catch exceptions
		}
		catch (Exception $e)
		{
			dd($e->getMessage());
		}
	}
}
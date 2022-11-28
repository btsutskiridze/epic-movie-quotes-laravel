<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
	public function redirectToGoogle()
	{
		return Socialite::driver('google')->stateless()->redirect();
	}

	public function handleGoogleCallback()
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
				return redirect(env('FRONT_URL'))->withCookie($cookie);
			}
			else
			{
				//user is not yet created, so create first
				$newUser = User::create([
					'name'     => $googleUser->name,
					'email'    => $googleUser->email,
					'password' => encrypt(''),
					'token'    => Str::random(60),
				]);

				$payload = [
					'exp' => Carbon::now()->addDay()->timestamp,
					'uid' => $newUser->id,
				];

				$jwt = JWT::encode($payload, config('auth.jwt_secret'), config('auth.jwt_algo'));

				$cookie = cookie('access_token', $jwt, 60, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');
				return redirect(env('FRONT_URL'))->withCookie($cookie);
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
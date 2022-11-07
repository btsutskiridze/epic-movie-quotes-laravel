<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
	public function redirectToGoogle()
	{
		return Socialite::driver('google')->redirect();
	}

	public function handleGoogleCallback()
	{
		try
		{
			//create a user using socialite driver google
			$googleUser = Socialite::driver('google')->user();
			// if the user exits, use that user and login
			$finduser = User::where('email', $googleUser->email)->get()->first();
			if ($finduser)
			{
				//if the user exists, login and show dashboard
				$jwt_token = strval(Auth::login($finduser));
				$expires_in = auth()->factory()->getTTL() * 60;
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
				$newUser->markEmailAsVerified();
				$newUser->save();
				//login as the new user
				$jwt_token = strval(Auth::login($newUser));
				$expires_in = auth()->factory()->getTTL() * 60;
				// go to the dashboard
			}
			return redirect(env('FRONT_URL') . 'google-redirect/?jwt_token=' . $jwt_token . '&expires_in=' . $expires_in);
			//catch exceptions
		}
		catch (Exception $e)
		{
			dd($e->getMessage());
		}
	}
}
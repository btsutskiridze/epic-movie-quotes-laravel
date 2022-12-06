<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerificationMail;
use App\Models\Email;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function register(RegisterRequest $request): JsonResponse
	{
		$user = User::create([
			'name'    => $request->name,
			'email'   => $request->email,
			'password'=> Hash::make($request->password),
			'token'   => Str::random(60),
		]);

		Mail::to($user->email)->send(new VerificationMail($user));

		return response()->json('User successfuly registered!', 200);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$input = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
		$value = $request[$input];

		$existsInUsers = User::where('email', $request[$input])->first();
		$existsInEmails = Email::where('email', $request[$input])->first();
		if ($input === 'email' && !$existsInUsers && $existsInEmails)
		{
			$value = User::where('id', $existsInEmails->user_id)->first()->email;
		}
		elseif (!$existsInEmails)
		{
			return response()->json(['errors'=> [
				'email'=> 'The selected email is invalid',
			]], 404);
		}

		$authenticated = auth()->attempt([
			$input       => $value,
			'password'   => $request->password,
		]);

		if (!$authenticated)
		{
			return response()->json(['errors'=> [
				'password'=> 'invalid password',
			]], 404);
		}
		$exp_time = $request->remember ? 525600 : 1440;

		$payload = [
			'exp' => Carbon::now()->addMinutes($exp_time)->timestamp,
			'uid' => User::where($input, $value)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), config('auth.jwt_algo'));

		$cookie = cookie('access_token', $jwt, $exp_time, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}

	public function logout(): JsonResponse
	{
		$cookie = cookie('access_token', '', 0, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}

	public function autoLogin(Request $request): JsonResponse
	{
		$myRequest = $request->email == null ? 'token' : 'email';
		$payload = [
			'exp' => Carbon::now()->addDay()->timestamp,
			'uid' => User::where($myRequest, $request[$myRequest])->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), config('auth.jwt_algo'));

		$cookie = cookie('access_token', $jwt, 30, '/', config('auth.front_end_top_level_domain'), true, true, false, 'Strict');

		return response()->json('success', 200)->withCookie($cookie);
	}

	public function me(): JsonResponse
	{
		return response()->json(
			[
				'message' => 'authenticated successfully',
				'user'    => jwtUser()->load('emails'),
			],
			200
		);
	}
}
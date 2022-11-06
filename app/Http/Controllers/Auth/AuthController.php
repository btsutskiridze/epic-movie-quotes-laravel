<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\VerificationMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
		$token = auth()->attempt($request->all());

		if (!$token)
		{
			return response()->json(['errors'=> [
				'password'=> 'invalid password',
			]], 404);
		}

		return $this->respondWithToken($token);
	}
}
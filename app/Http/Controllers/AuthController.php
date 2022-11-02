<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function register(RegisterRequest $request)
	{
		User::create([
			'name'    => $request->name,
			'email'   => $request->email,
			'password'=> Hash::make($request->password),
		]);

		return response()->json('User successfuly registered!', 200);
	}
}
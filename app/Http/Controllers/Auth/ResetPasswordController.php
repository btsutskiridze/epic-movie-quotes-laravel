<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
	public function sentEmail(Request $request): JsonResponse
	{
		$request->validate(['email' => 'required|exists:users,email']);

		$token = Str::random(64);

		DB::table('password_resets')->insert([
			'email'      => $request->email,
			'token'      => $token,
			'created_at' => Carbon::now(),
		]);

		$user = User::where('email', $request->email)->first();
		Mail::to($request->email)->send(new ResetPasswordMail($user, $token));

		return response()->json('message sent');
	}

	public function updatePassword(Request $request): JsonResponse
	{
		$request->validate([
			'token'                 => 'required',
			'email'                 => 'required|exists:users,email',
			'password'              => 'required|min:4|confirmed',
			'password_confirmation' => 'required',
		]);

		$updatePassword = DB::table('password_resets')
			->where([
				'email' => $request->email,
				'token' => $request->token,
			])
			->first();

		if (!$updatePassword)
		{
			throw ValidationException::withMessages([
				'error'=> 'Invalid token!',
			]);
		}

		User::where('email', $updatePassword->email)
			->update(['password'=>Hash::make($request->password)]);

		DB::table('password_resets')->where(['email'=>$request->email])->delete();

		return response()->json('success');
	}
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
	public function sentEmail(ForgetPasswordRequest $request): JsonResponse
	{
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

	public function updatePassword(ResetPasswordRequest $request): JsonResponse
	{
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

		return response()->json('password updated');
	}
}
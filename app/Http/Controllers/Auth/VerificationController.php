<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
	public function verifyEmail(Request $request): JsonResponse
	{
		$user = User::where('token', $request->token)->get()->first();
		if ($user != null)
		{
			$user->markEmailAsVerified();
			$token = strval(auth()->login($user));
			return $this->respondWithToken($token);
		}
		return response()->json(['error'=>'Email Verification failed'], 422);
	}
}
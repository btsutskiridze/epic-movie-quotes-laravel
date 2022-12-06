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
		if (isset($user))
		{
			$user->markEmailAsVerified();
			return response()->json('verification success');
		}
		return response()->json(['error'=>'Email Verification failed'], 422);
	}
}

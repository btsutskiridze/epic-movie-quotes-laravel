<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerificationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
	public function verifyEmail(VerificationRequest $request): JsonResponse
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
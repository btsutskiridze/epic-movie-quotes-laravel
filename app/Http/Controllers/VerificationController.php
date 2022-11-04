<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
	public function verifyEmail(Request $request)
	{
		$user = User::where('token', $request->token)->get();
		$user->email_verified_at = now();
		return response()->json('Email Verified Successfully', 200);
	}
}
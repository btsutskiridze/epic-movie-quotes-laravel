<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
	public function verifyEmail(Request $request)
	{
		$user = User::where('token', $request->token)->get()->first();
		$user->markEmailAsVerified();
		return response()->json('Email Verified Successfully', 200);
	}
}

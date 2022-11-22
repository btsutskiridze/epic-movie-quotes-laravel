<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationMiddleware
{
	public function handle(Request $request, Closure $next): JsonResponse
	{
		$user = User::where('email', $request->email)->first();
		if ($user && !isset($user->email_verified_at))
		{
			return response()->json(['errors'=> [
				'verification'=> 'email is not verified',
			]], 401);
		}
		return $next($request);
	}
}
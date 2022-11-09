<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationMiddleware
{
	public function handle(Request $request, Closure $next): JsonResponse
	{
		if (!$request->user() ||
		($request->user() instanceof MustVerifyEmail &&
		!$request->user()->hasVerifiedEmail()))
		{
			return response()->json(['errors'=> [
				'verification'=> 'email is not verified',
			]], 404);
		}
		return $next($request);
	}
}
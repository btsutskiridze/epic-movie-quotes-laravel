<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\NewEmailVerificationRequest;
use App\Models\Email;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\NewEmailMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
	public function store(EmailRequest $request): JsonResponse
	{
		$email = Email::create([
			'user_id' => jwtUser()->id,
			'token'   => Str::random(60),
			'email'   => $request->email,
		]);

		Mail::to(jwtUser()->email)->send(new NewEmailMail(jwtUser(), $email));

		return response()->json('email added');
	}

	public function verification(NewEmailVerificationRequest $request): JsonResponse
	{
		$email = Email::where('token', $request->token)->first();
		if (isset($email))
		{
			$email->email_verified_at = Carbon::now();
			$email->save();
			return response()->json('verification success', 201);
		}
		return response()->json(['error'=>'Email Verification failed'], 422);
	}

	public function delete(Email $email): JsonResponse
	{
		$email->delete();
		return response()->json('email deleted', 201);
	}

	public function makePrimary(Email $email): JsonResponse
	{
		$user = User::where('id', jwtUser()->id)->first();
		$newSecondaryMail = $user->email;

		//replace
		$user->email = $email->email;
		$user->save();

		$email->email = $newSecondaryMail;
		$email->save();

		return response()->json('email replaced successfully', 201);
	}
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\NewEmailMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
	public function store(EmailRequest $request)
	{
		$email = Email::create([
			'user_id' => jwtUser()->id,
			'token'   => Str::random(60),
			'email'   => $request->email,
		]);

		Mail::to(jwtUser()->email)->send(new NewEmailMail(jwtUser(), $email));
	}

	public function verification(Request $request): JsonResponse
	{
		$email = Email::where('token', $request->token)->first();
		if (isset($email))
		{
			$email->email_verified_at = Carbon::now();
			$email->save();
			return response()->json('verification success', 200);
		}
		return response()->json(['error'=>'Email Verification failed'], 422);
	}

	public function delete(Email $email)
	{
		$email->delete();
		return response()->json('email deleted', 200);
	}

	public function makePrimary(Email $email)
	{
		$user = User::where('id', jwtUser()->id)->first();
		$nonPrimaryEmail = $user->email;

		//replace
		$user->email = $email->email;
		$user->save();

		//remove old
		$email->delete();

		//add new
		$newEmail = Email::create([
			'user_id'           => jwtUser()->id,
			'token'             => Str::random(60),
			'email'             => $nonPrimaryEmail,
		]);
		$newEmail->email_verified_at = Carbon::now();
		$newEmail->save();

		return response()->json('email replaced successfully', 200);
	}
}
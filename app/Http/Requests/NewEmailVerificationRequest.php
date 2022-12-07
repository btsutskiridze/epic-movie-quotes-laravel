<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewEmailVerificationRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'token'=> 'required|exists:users,token',
		];
	}
}
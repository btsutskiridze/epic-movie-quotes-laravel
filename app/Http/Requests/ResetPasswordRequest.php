<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'token'                 => 'required',
			'email'                 => 'required|exists:users,email',
			'password'              => 'required|confirmed',
			'password_confirmation' => 'required',
		];
	}
}
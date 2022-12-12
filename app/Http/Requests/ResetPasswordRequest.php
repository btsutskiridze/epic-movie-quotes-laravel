<?php

namespace App\Http\Requests;

use App\Rules\LowerCase;
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
			'password'              => ['required', 'confirmed', 'min:8', 'max:15', new LowerCase],
			'password_confirmation' => 'required',
		];
	}
}
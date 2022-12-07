<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoLoginRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'token'=> 'exists:users,token',
			'email'=> 'exists:users,email',
		];
	}
}
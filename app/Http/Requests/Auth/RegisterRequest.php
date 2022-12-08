<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name'    => 'required|unique:users,name',
			'email'   => 'required|email|unique:users,email',
			'password'=> 'required',
		];
	}
}
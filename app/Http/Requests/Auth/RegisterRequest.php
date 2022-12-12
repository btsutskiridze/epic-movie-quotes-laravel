<?php

namespace App\Http\Requests\Auth;

use App\Rules\LowerCase;
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
			'name'    => ['required', 'min:3', 'max:15', 'unique:users,name', new LowerCase],
			'email'   => 'required|email|unique:users,email',
			'password'=> ['required', 'min:8', 'max:15', new LowerCase],
		];
	}
}
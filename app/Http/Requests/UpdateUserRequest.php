<?php

namespace App\Http\Requests;

use App\Rules\LowerCase;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'name' => ['unique:users,name', 'min:3', 'max:15', new LowerCase],
		];
	}
}
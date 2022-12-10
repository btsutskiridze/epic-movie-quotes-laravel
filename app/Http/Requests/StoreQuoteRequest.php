<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'movie_id' => 'required|exists:movies,id',
			'thumbnail'=> 'required',
			'title_en' => 'required',
			'title_ka' => 'required',
		];
	}
}
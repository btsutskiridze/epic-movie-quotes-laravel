<?php

namespace App\Http\Requests;

use App\Rules\EngTextarea;
use App\Rules\GeoTextarea;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'title_en'      => ['required', new EngTextarea],
			'title_ka'      => ['required', new GeoTextarea],
		];
	}
}
<?php

namespace App\Http\Requests;

use App\Rules\Eng;
use App\Rules\EngTextarea;
use App\Rules\Geo;
use App\Rules\GeoTextarea;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'title_en'      => [new Eng],
			'title_ka'      => [new Geo],
			'director_en'   => [new Eng],
			'director_ka'   => [new Geo],
			'description_en'=> [new EngTextarea],
			'description_ka'=> [new GeoTextarea],
		];
	}
}
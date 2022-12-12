<?php

namespace App\Http\Requests;

use App\Rules\Eng;
use App\Rules\EngTextarea;
use App\Rules\Geo;
use App\Rules\GeoTextarea;
use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'title_en'      => ['required', new Eng],
			'title_ka'      => ['required', new Geo],
			'genre'         => ['required'],
			'director_en'   => ['required', new Eng],
			'director_ka'   => ['required', new Geo],
			'description_en'=> ['required', new EngTextarea],
			'description_ka'=> ['required', new GeoTextarea],
			'year'          => ['required'],
			'budget'        => ['required'],
			'thumbnail'     => ['required'],
		];
	}
}
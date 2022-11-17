<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
	public function store(StoreMovieRequest $request): JsonResponse
	{
		$movie = new Movie();
		$movie->user_id = auth()->id();
		$movie->setTranslation('title', 'en', $request->title_en);
		$movie->setTranslation('title', 'ka', $request->title_ka);
		$movie->genre = json_encode($request->genre);
		$movie->setTranslation('director', 'en', $request->director_en);
		$movie->setTranslation('director', 'ka', $request->director_ka);
		$movie->setTranslation('description', 'en', $request->description_en);
		$movie->setTranslation('description', 'ka', $request->description_ka);
		$movie->year = $request->year;
		$movie->budget = $request->budget;
		$movie->thumbnail = $request->file('thumbnail')->store('thumbnails');
		$movie->save();

		return response()->json('movie added', 200);
	}

	public function index()
	{
		return response()->json(auth()->user()->movies);
	}
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(jwtUser()->movies->load('quotes'));
	}

	public function store(StoreMovieRequest $request): JsonResponse
	{
		$movie = new Movie();
		$movie->user_id = jwtUser()->id;
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

		return response()->json('movie added');
	}

	public function get(Movie $movie): JsonResponse
	{
		if ($movie->user_id !== jwtUser()->id)
		{
			return response()->json('not authorized', 401);
		}
		return response()->json($movie->load(['quotes.comments', 'quotes.likes']));
	}

	public function update(Movie $movie, Request $request): JsonResponse
	{
		if ($movie->user_id !== jwtUser()->id)
		{
			return response()->json('not authorized', 401);
		}

		$movie->setTranslation('title', 'en', $request->title_en);
		$movie->setTranslation('title', 'ka', $request->title_ka);
		$movie->setTranslation('director', 'en', $request->director_en);
		$movie->setTranslation('director', 'ka', $request->director_ka);
		$movie->setTranslation('description', 'en', $request->description_en);
		$movie->setTranslation('description', 'ka', $request->description_ka);
		$movie->year = $request->year;
		$movie->budget = $request->budget;

		if (isset($request->genre))
		{
			$movie->genre = json_encode($request->genre);
		}

		if (isset($request->thumbnail))
		{
			Storage::delete($movie->thumbnail);
			$movie->thumbnail = $request->file('thumbnail')->store('thumbnails');
		}

		$movie->update();

		return response()->json(['movie updated', 'movie'=>$movie->load(['quotes.comments', 'quotes.likes'])]);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		if ($movie->user_id !== jwtUser()->id)
		{
			return response()->json('not authorized', 401);
		}

		$movie->delete();
		return response()->json(['movie deleted', 'movies'=>Movie::all()->load('quotes')]);
	}
}
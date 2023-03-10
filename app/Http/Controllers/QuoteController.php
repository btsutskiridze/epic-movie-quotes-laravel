<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyQuoteRequest;
use App\Http\Requests\NumberQuotesRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\StoreQuoteRequest;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
	public function index()
	{
		return response()->json(Quote::with(['movie', 'comments.author', 'user'])->withCount('likes')->orderBy('updated_at', 'DESC')->paginate(2));
	}

	public function numberQuotes(NumberQuotesRequest $request): JsonResponse
	{
		return response()->json(
			Quote::query()->take($request->count)
			->with(['movie', 'comments.author', 'user'])->withCount('likes')
			->orderBy('updated_at', 'DESC')
			->get()
		);
	}

	public function search(SearchRequest $request): JsonResponse
	{
		$search = $request->search;
		if ($search[0] === '@')
		{
			$search = substr($search, 1);
			return $this->QuotesResponse($search, 'movie');
		}

		if ($search[0] === '#')
		{
			$search = ltrim($search, '#');
			return $this->QuotesResponse($search);
		}

		return $this->QuotesResponse($search);
	}

	private function QuotesResponse($search, $type = 'quote'): JsonResponse
	{
		return $type == 'quote' ?
			response()->json(
				Quote::where('title->en', 'LIKE', $search . '%')
				->orWhere('title->ka', 'LIKE', $search . '%')
					->with(['movie', 'comments.author', 'user'])->withCount('likes')->orderBy('updated_at', 'DESC')
					->get()
			)

		: response()->json(
			Quote::whereHas('movie', function ($query) use ($search) {
				$query->where('title->en', 'LIKE', $search . '%')
				->orWhere('title->ka', 'LIKE', $search . '%');
			})
			->with(['movie', 'comments.author', 'user'])->withCount('likes')->orderBy('updated_at', 'DESC')
			->get()
		);
	}

	public function store(StoreQuoteRequest $request): JsonResponse
	{
		$quote = new Quote();
		$quote->user_id = jwtUser()->id;
		$quote->movie_id = $request->movie_id;
		$quote->thumbnail = $request->file('thumbnail')->store('thumbnails');
		$quote->setTranslation('title', 'en', $request->title_en);
		$quote->setTranslation('title', 'ka', $request->title_ka);
		$quote->save();

		return response()->json(
			[
				'quote added',
				'movie'=> Movie::where('id', $request->movie_id)
					->with(['quotes.comments', 'quotes.likes'])
					->first(),
			]
		);
	}

	public function update(Quote $quote, Request $request): JsonResponse
	{
		$quote->setTranslation('title', 'en', $request->title_en);
		$quote->setTranslation('title', 'ka', $request->title_ka);

		if (isset($request->thumbnail))
		{
			Storage::delete($quote->thumbnail);
			$quote->thumbnail = $request->file('thumbnail')->store('thumbnails');
		}

		$quote->update();

		return response()->json([
			'quote updated',
			'movie'=> Movie::where('id', $request->movie_id)
				->with(['quotes.comments', 'quotes.likes'])
				->first(),
		]);
	}

	public function get(Quote $quote): JsonResponse
	{
		if ($quote->user_id !== jwtUser()->id)
		{
			return response()->json('not authorized', 401);
		}
		return response()->json($quote);
	}

	public function getWithRelations(Quote $quote)
	{
		if ($quote->user_id !== jwtUser()->id)
		{
			return response()->json('not authorized', 401);
		}
		return response()->json($quote->load(['user', 'movie', 'comments.author'])->loadCount('likes'));
	}

	public function destroy(Quote $quote, DestroyQuoteRequest $request): JsonResponse
	{
		if ($quote->user_id !== jwtUser()->id)
		{
			return response()->json('not authorized', 401);
		}
		$quote->delete();
		return response()->json([
			'quote deleted',
			'movie'=> Movie::where('id', $request->movie_id)
				->with(['quotes.comments', 'quotes.likes'])
				->first(),
		]);
	}
}
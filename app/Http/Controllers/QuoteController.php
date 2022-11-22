<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
	public function index()
	{
		return response()->json(jwtUser()->quotes);
	}

	public function store(Request $request)
	{
		$quote = new Quote();
		$quote->user_id = jwtUser()->id;
		$quote->movie_id = $request->movie_id;
		$quote->thumbnail = $request->file('thumbnail')->store('thumbnails');
		$quote->setTranslation('title', 'en', $request->title_en);
		$quote->setTranslation('title', 'ka', $request->title_ka);
		$quote->save();

		return response()->json('quote added', 200);
	}
}
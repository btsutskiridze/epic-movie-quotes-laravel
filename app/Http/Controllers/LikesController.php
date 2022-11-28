<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Quote;

class LikesController extends Controller
{
	public function likable(Quote $quote)
	{
		$like = Like::where('user_id', jwtUser()->id)->where('quote_id', $quote->id)->first();
		return response()->json(['likable'=>$like ? false : true]);
	}

	public function like(Quote $quote)
	{
		$like = Like::where('user_id', jwtUser()->id)->where('quote_id', $quote->id)->first();

		if ($like)
		{
			$like->delete();
			return response('like removed');
		}

		$quote->likes()->create([
			'user_id' => jwtUser()->id,
		]);
		return response('like added');
	}
}
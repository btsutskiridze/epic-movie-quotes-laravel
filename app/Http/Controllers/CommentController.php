<?php

namespace App\Http\Controllers;

use App\Events\AddCommentEvent;
use App\Models\Quote;
use Illuminate\Http\Request;

class CommentController extends Controller
{
	public function store(Quote $quote, Request $request)
	{
		event(new AddCommentEvent($request->all()));

		$quote->comments()->create([
			'user_id' => jwtUser()->id,
			'body'    => $request->body,
		]);

		return response()->json('comment added', 200);
	}
}
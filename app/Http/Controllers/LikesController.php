<?php

namespace App\Http\Controllers;

use App\Events\AddLikeEvent;
use App\Events\NotificationEvent;
use App\Http\Requests\LikeRequest;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class LikesController extends Controller
{
	public function likable(Quote $quote): JsonResponse
	{
		$like = Like::where('user_id', jwtUser()->id)->where('quote_id', $quote->id)->first();
		return response()->json(['likable'=>$like ? false : true]);
	}

	public function like(Quote $quote, LikeRequest $request): JsonResponse
	{
		event(new AddLikeEvent($request->all()));

		$like = Like::where('user_id', jwtUser()->id)->where('quote_id', $quote->id)->first();

		if ($like)
		{
			$like->delete();
			return response()->json('like removed');
		}

		$quote->likes()->create([
			'user_id' => jwtUser()->id,
		]);

		if ($request->to_id !== jwtUser()->id)
		{
			$notification = Notification::create([
				'from_id'  => $request->from_id,
				'user_id'  => $request->to_id,
				'type'     => 'like',
				'read'     => false,
			]);
			event(new NotificationEvent($notification));
		}
		return response()->json('like added');
	}
}
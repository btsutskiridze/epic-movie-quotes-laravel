<?php

namespace App\Http\Controllers;

use App\Events\AddCommentEvent;
use App\Events\NotificationEvent;
use App\Http\Requests\CommentRequest;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
	public function store(Quote $quote, CommentRequest $request): JsonResponse
	{
		event(new AddCommentEvent($request->all()));

		$quote->comments()->create([
			'user_id' => jwtUser()->id,
			'body'    => $request->body,
		]);

		if ($request->to_id !== jwtUser()->id)
		{
			$notification = Notification::create([
				'from_id'  => $request->from_id,
				'user_id'  => $request->to_id,
				'type'     => 'comment',
				'read'     => false,
			]);
			event(new NotificationEvent($notification->load('from')));
		}

		return response()->json('comment added');
	}
}
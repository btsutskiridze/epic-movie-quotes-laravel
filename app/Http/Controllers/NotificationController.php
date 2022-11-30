<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationController extends Controller
{
	public function index(): JsonResponse
	{
		$user_id = jwtUser()->id;
		$notifications = Notification::where('user_id', $user_id)
									->with(['from', 'user'])
									->orderBy('created_at', 'desc')
									->get();

		$readNotifications = Notification::where('user_id', $user_id)->where('read', 1)->get();
		return response()->json(
			[
				'notifications'=> $notifications,
				'allRead'      => count($notifications) === count($readNotifications),
				'read'         => count($readNotifications),
			]
		);
	}

	public function read(): JsonResponse
	{
		Notification::query()->where('user_id', jwtUser()->id)->update(['read' => 1]);
		return response()->json('all read');
	}
}
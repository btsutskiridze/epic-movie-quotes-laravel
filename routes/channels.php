<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('add-comment-channel', function () {
	return true;
});

Broadcast::channel('like-channel', function () {
	return true;
});

Broadcast::channel('notification.{id}', function ($user, $id) {
	return (int) $user->id === (int) $id;
});
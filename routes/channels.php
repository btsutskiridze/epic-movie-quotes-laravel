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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
	return (int) jwtUser()->id === (int) $id;
});

Broadcast::channel('user-notification.{id}', function ($user, $id) {
	return (int) jwtUser()->id === (int) $id;
});

Broadcast::channel('add-comment-channel', function () {
	return true;
});

Broadcast::channel('like-channel', function () {
	return true;
});
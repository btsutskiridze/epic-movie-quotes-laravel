<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
	public function update(UpdateUserRequest $request)
	{
		$user = User::where('id', jwtUser()->id)->first();
		$request->all();
		if (isset($request->name))
		{
			$user->name = $request->name;
		}

		if (isset($request->image))
		{
			if (!str_contains($user->avatar, 'images/avatar.png'))
			{
				Storage::delete($user->avatar);
			}
			$user->avatar = config('app.storage_url') . $request->file('image')->store('avatars');
		}

		if (isset($request->password))
		{
			$user->password = bcrypt($request->password);
		}
		$user->save();

		return response()->json(['user updated']);
	}
}

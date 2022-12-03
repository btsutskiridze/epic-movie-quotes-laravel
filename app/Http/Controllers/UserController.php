<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function update(Request $request)
	{
		$user = User::where('id', jwtUser()->id)->first();
		if (isset($request->name))
		{
			$user->name = $request->name;
		}

		if (isset($request->password))
		{
			$user->password = bcrypt($request->password);
		}
		$user->save();

		return response()->json(['user updated'], 200);
	}
}
<?php

namespace App\Guards;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtGuard
{
	public $user = null;

	public function check()
	{
		if ($this->user())
		{
			return true;
		}
		return false;
	}

	public function user()
	{
		if ($this->user)
		{
			return $this->user;
		}

		try
		{
			if (!request()->cookie('access_token') && !request()->header('Authorization'))
			{
				return null;
			}

			$decoded = JWT::decode(
				request()->cookie('access_token') ?? substr(request()->header('Authorization'), 7),
				new Key(config('auth.jwt_secret'), config('auth.jwt_algo'))
			);
			$this->user = User::find($decoded->uid);
			return User::find($decoded->uid);
		}
		catch(Exception $e)
		{
			return null;
		}
	}
}
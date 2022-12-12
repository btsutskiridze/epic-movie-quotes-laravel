<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\Rule;

class EngTextarea implements InvokableRule
{
	/**
	 * Run the validation rule.
	 *
	 * @param string   $attribute
	 * @param mixed    $value
	 * @param \Closure $fail
	 *
	 * @return void
	 */
	public function __invoke($attribute, $value, $fail)
	{
		if (!preg_match('/^[a-zA-Z0-9-_\';?!:.,"\s]*$/', $value))
		{
			$fail('The :attribute must be in english.');
		}
	}
}
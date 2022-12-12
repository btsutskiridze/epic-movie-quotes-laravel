<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\Rule;

class LowerCase implements InvokableRule
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
		if (strtolower($value) !== $value)
		{
			$fail('The :attribute must be lowercase.');
		}
	}
}
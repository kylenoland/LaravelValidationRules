<?php namespace KyleNoland\LaravelValidationRules;

use Illuminate\Validation\Validator;

class LaravelValidationRules extends Validator
{
	/**
	 * Determine if an array contains only date values
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateArrayDates($attribute, $value, $parameters)
	{
		foreach($value as $v)
		{
			$parsed = date_parse_from_format($parameters[0], $v);

			if($parsed['error_count'] !== 0)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Determine if a single dimension array contains only integers
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateArrayIntegers($attribute, $value, $parameters)
	{
		foreach($value as $v)
		{
			if( ! ctype_digit($v))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Determine if an array contains only integers or empty values
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateArrayIntegersOrEmpty($attribute, $value, $parameters)
	{
		foreach($value as $v)
		{
			if(empty($v))
			{
				continue;
			}

			if( ! ctype_digit($v))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Determine if a multidimensional array contains only integers
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateArrayIntegersRecursive($attribute, $value, $parameters)
	{
		$flat = array_flatten($value);

		foreach($flat as $v)
		{
			if( ! ctype_digit($v))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Determine if an array contains any dates before the specified date
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateArrayNotBefore($attribute, $value, $parameters)
	{
		foreach($value as $v)
		{
			$start = strtotime($parameters[0]);
			$desired = strtotime($v);

			if($desired < $start)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Determine if a given date string is a future date
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 *
	 * @return bool
	 */
	public function validateFuture($attribute, $value, $parameters)
	{
		$now = strtotime('now');
		$date = strtotime($value);

		return $date > $now;
	}

	/**
	 * Determine if a given date string is a past date
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 *
	 * @return bool
	 */
	public function validatePast($attribute, $value, $parameters)
	{
		return ! $this->validateFuture($attribute, $value, $parameters);
	}

	/**
	 * Determine if a given date string is a future date or today
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 *
	 * @return bool
	 */
	public function validateTodayOrFuture($attribute, $value, $parameters)
	{
		$now = strtotime('now');
		$date = strtotime($value);

		return $date >= $now;
	}

	/**
	 * Determine if a given date string is a past date or today
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 *
	 * @return bool
	 */
	public function validateTodayOrPast($attribute, $value, $parameters)
	{
		$now = strtotime('now');
		$date = strtotime($value);

		return $date <= $now;
	}

	/**
	 * Determine if an array is not completely empty
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateArrayNotEmpty($attribute, $value, $parameters)
	{
		foreach($value as $v)
		{
			if( ! empty($v))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Determine if an array contains only numeric values
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateArrayNumeric($attribute, $value, $parameters)
	{
		foreach($value as $v)
		{
			if( ! is_numeric($v))
			{
				return false;
			}
		}

		return true;
	}


	/**
	 * Is the value a valid currency (USD) value
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 *
	 * @return bool
	 */
	public function validateCurrency($attribute, $value, $parameters)
	{
		return (bool) preg_match('"^\$?\-?([1-9]{1}[0-9]{0,2}(\,\d{3})*(\.\d{0,2})?|[1-9]{1}\d{0,}(\.\d{0,2})?|0(\.\d{0,2})?|(\.\d{1,2}))$|^\-?\$?([1-9]{1}\d{0,2}(\,\d{3})*(\.\d{0,2})?|[1-9]{1}\d{0,}(\.\d{0,2})?|0(\.\d{0,2})?|(\.\d{1,2}))$|^\(\$?([1-9]{1}\d{0,2}(\,\d{3})*(\.\d{0,2})?|[1-9]{1}\d{0,}(\.\d{0,2})?|0(\.\d{0,2})?|(\.\d{1,2}))\)$"', $value);
	}


	/**
	 * Determine if the value is greater than the specified amount
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateGreaterThan($attribute, $value, $parameters)
	{
		return $value > $parameters[0];
	}

	/**
	 * Determine if the value is less than the specified amount
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateLessThan($attribute, $value, $parameters)
	{
		return $value < $parameters[0];
	}

	/**
	 * Enforce a minimum value depending on the value of other attributes being validated
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 *
	 * @return bool
	 */
	public function validateMinIf($attribute, $value, $parameters)
	{
		$column = $parameters[0];
		$columnValue = $parameters[1];
		$minValue = $parameters[2];

		if($this->getValue($column) == $columnValue)
		{
			return $this->getValue($attribute) >= $minValue;
		}

		return true;
	}

	/**
	 * Enforce an integer value depending on the value of other attributes being validated
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 *
	 * @return bool
	 */
	public function validateIntegerIf($attribute, $value, $parameters)
	{
		$column = $parameters[0];
		$columnValue = $parameters[1];

		if($this->getValue($column) == $columnValue)
		{
			return ctype_digit($this->getValue($attribute));
		}

		return true;
	}

	/**
	 * Determine if the value is a phone number
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validatePhone($attribute, $value, $parameters)
	{
		$regex = "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i";

		return preg_match($regex, $value);
	}

	/**
	 * Determine if the value is a negative number
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateNegative($attribute, $value, $parameters)
	{
		return is_numeric($value) and $value < 0;
	}

	/**
	 * Determine if the value is a positive number
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validatePositive($attribute, $value, $parameters)
	{
		return is_numeric($value) and $value >= 0;
	}

	/**
	 * Determine if the value is a social security number
	 *
	 * @param  $attribute
	 * @param  $value
	 * @param  $parameters
	 *
	 * @return bool
	 */
	public function validateSsn($attribute, $value, $parameters)
	{
		return strlen(preg_replace('/\D/', '', $value)) == 9;
	}
	

	/**
	 * Verify that a collection of attributes are mutually exclusive with each other
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 *
	 * @return bool
	 */
	public function validateMutuallyExclusiveWith($attribute, $value, $parameters)
	{
		foreach($parameters as $key)
		{
			$val = $this->getValue($key);

			if( ! empty($val))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Returns an array with value and column name for an optional ignore.
	 * Shaves of the ignore_id from the end of the array, if there is one.
	 *
	 * @param  array $parameters
	 * @return array [$ignoreId, $ignoreColumn]
	 */
	private function getIgnore(&$parameters)
	{
		$lastParam = end($parameters);
		$lastParam = array_map('trim', explode('=', $lastParam));

		// An ignore_id is only specified if the last param starts with a
		// number greater than 1 (a valid id in the database)
		if (!preg_match('/^[1-9][0-9]*$/', $lastParam[0]))
		{
			return array(null, null);
		}

		$ignoreId = $lastParam[0];
		$ignoreColumn = (sizeof($lastParam) > 1) ? end($lastParam) : null;

		// Shave of the ignore_id from the array for later processing
		array_pop($parameters);

		return array($ignoreId, $ignoreColumn);
	}

	/**
	 * Replace all place-holders for the mutually_exclusive_with rule.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceMutuallyExclusiveWith($message, $attribute, $rule, $parameters)
	{
		$parameters = $this->getAttributeList($parameters);

		return str_replace(':values', implode(' or ', $parameters), $message);
	}

	/**
	 * Replace all place-holders for the greater_than rule
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceGreaterThan($message, $attribute, $rule, $parameters)
	{
		$parameters = $this->getAttributeList($parameters);

		return str_replace(':floor', $parameters[0], $message);
	}

	/**
	 * Replace all place-holders for the less_than rule
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceLessThan($message, $attribute, $rule, $parameters)
	{
		$parameters = $this->getAttributeList($parameters);

		return str_replace(':ceiling', $parameters[0], $message);
	}

	/**
	 * Replace all place-holders for the min_if rule
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replaceMinIf($message, $attribute, $rule, $parameters)
	{
		$parameters = $this->getAttributeList($parameters);

		return str_replace(':floor', $parameters[2], $message);
	}

	/**
	 * @param $message
	 * @param $attribute
	 * @param $rule
	 * @param $parameters
	 *
	 * @return mixed
	 */
	public function replaceUniqueWith($message, $attribute, $rule, $parameters)
	{
		// merge primary field with conditional fields
		$fields = array($attribute) + $parameters;

		// get full language support due to mapping to validator getAttribute
		// function
		$fields = array_map(array($this, 'getAttribute'), $fields);

		// fields to string
		$fields = implode(', ', $fields);

		return str_replace(':fields', $fields, $message);
	}

}
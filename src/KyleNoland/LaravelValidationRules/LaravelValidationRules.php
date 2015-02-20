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
	 * Usage: unique_with: table, column1, column2, ...
	 *
	 * @param  string $attribute
	 * @param  mixed  $value
	 * @param  array $parameters
	 * @return boolean
	 */
	public function validateUniqueWith($attribute, $value, $parameters)
	{
		// cleaning: trim whitespace
		$parameters = array_map('trim', $parameters);

		// first item equals table name
		$table = array_shift($parameters);

		// The second parameter position holds the name of the column that
		// needs to be verified as unique. If this parameter isn't specified
		// we will just assume that this column to be verified shares the
		// attribute's name.
		$column = $attribute;

		// Create $extra array with all other columns, so getCount() will
		// include them as where clauses as well
		$extra = array();

		// Check if last parameter is an integer. If it is, then it will
		// ignore the row with the specified id - useful when updating a row
		list($ignore_id, $ignore_column) = $this->getIgnore($parameters);

		// Figure out whether field_name is the same as column_name
		// or column_name is explicitly specified.
		//
		// case 1:
		//     $parameter = 'last_name'
		//     => field_name = column_name = 'last_name'
		// case 2:
		//     $parameter = 'last_name=sur_name'
		//     => field_name = 'last_name', column_name = 'sur_name'
		foreach ($parameters as $parameter)
		{
			$parameter = array_map('trim', explode('=', $parameter, 2));
			$field_name = $parameter[0];

			if (count($parameter) > 1)
			{
				$column_name = $parameter[1];
			}
			else
			{
				$column_name = $field_name;
			}

			// Figure out whether main field_name has an explicitly specified
			// column_name
			if ($field_name == $column)
			{
				$column = $column_name;
			}
			else
			{
				$extra[$column_name] = array_get($this->data, $field_name);
			}
		}

		// The presence verifier is responsible for counting rows within this
		// store mechanism which might be a relational database or any other
		// permanent data store like Redis, etc. We will use it to determine
		// uniqueness.
		$verifier = $this->getPresenceVerifier();

		return $verifier->getCount(
			$table,
			$column,
			$value,
			$ignore_id,
			$ignore_column,
			$extra
		) == 0;
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
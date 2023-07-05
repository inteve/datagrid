<?php

	namespace Inteve\DataGrid\Columns;


	class TextColumn extends AbstractColumn
	{
		protected function processDefaultFormat($value, $row)
		{
			if (is_scalar($value) || $value === NULL || (is_object($value) && method_exists($value, '__toString'))) {
				return (string) $value;
			}

			throw new \Inteve\DataGrid\InvalidArgumentException("Value type '" . gettype($value) . "' is not accepted.");
		}
	}

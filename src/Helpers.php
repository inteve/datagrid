<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class Helpers
	{
		public function __construct()
		{
			throw new StaticClassException('This is static class.');
		}


		/**
		 * @param  object|array
		 * @param  string
		 * @return scalar|NULL
		 */
		public static function getRowValue($row, $field)
		{
			if (is_array($row)) {
				return $row[$field];
			}

			return $row->{$field};
		}


		/**
		 * @param  array|NULL
		 * @param  array|NULL
		 * @return array|NULL
		 */
		public static function mergeAttributes(array $attributes = NULL, array $defaultAttributes = NULL)
		{
			if (empty($attributes)) {
				return $defaultAttributes;
			}

			if (!empty($defaultAttributes)) {
				foreach ($defaultAttributes as $attr => $value) {
					if (!isset($attributes[$attr])) {
						$attributes[$attr] = $value;

					} else {
						if (!is_array($attributes[$attr])) {
							$attributes[$attr] = array($attributes[$attr]);
						}

						if (is_array($value)) {
							foreach ($value as $val) {
								$attributes[$attr][] = $val;
							}

						} else {
							$attributes[$attr][] = $value;
						}
					}
				}
			}

			return $attributes;
		}
	}

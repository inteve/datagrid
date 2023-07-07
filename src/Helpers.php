<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class Helpers
	{
		public function __construct()
		{
			throw new StaticClassException('This is static class.');
		}


		/**
		 * @param  object|array<string, mixed> $row
		 * @param  string $field
		 * @return mixed|NULL
		 */
		public static function getRowValue($row, $field)
		{
			if (is_array($row)) {
				return $row[$field];
			}

			return $row->{$field};
		}


		/**
		 * @param  array<string, mixed>|NULL $attributes
		 * @param  array<string, mixed>|NULL $defaultAttributes
		 * @return array<string, mixed>|NULL
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
							$attributes[$attr] = [$attributes[$attr]];
						}

						if (is_array($value)) {
							foreach ($value as $val) {
								assert(is_array($attributes[$attr]));
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

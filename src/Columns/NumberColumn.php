<?php

	namespace Inteve\DataGrid\Columns;


	class NumberColumn extends AbstractColumn
	{
		/** @var string|NULL */
		private $prefix;

		/** @var string|NULL */
		private $suffix;

		/** @var int */
		private $decimals = 2;


		/**
		 * @param  string|NULL $prefix
		 * @return $this
		 */
		public function setPrefix($prefix)
		{
			$this->prefix = $prefix;
			return $this;
		}


		/**
		 * @param  string|NULL $suffix
		 * @return $this
		 */
		public function setSuffix($suffix)
		{
			$this->suffix = $suffix;
			return $this;
		}


		/**
		 * @param  int $decimals
		 * @return $this
		 */
		public function setDecimals($decimals)
		{
			$this->decimals = $decimals;
			return $this;
		}


		protected function processDefaultFormat($value, $row)
		{
			if (is_int($value) || is_float($value)) {
				return $this->prefix . number_format($value, $this->decimals, ',', '') . $this->suffix;
			}

			if (is_scalar($value) || $value === NULL || (is_object($value) && method_exists($value, '__toString'))) {
				return (string) $value;
			}

			throw new \Inteve\DataGrid\InvalidArgumentException("Value type '" . gettype($value) . "' is not accepted.");
		}
	}

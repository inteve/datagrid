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
		 * @param  string|NULL
		 * @return static
		 */
		public function setPrefix($prefix)
		{
			$this->prefix = $prefix;
			return $this;
		}


		/**
		 * @param  string|NULL
		 * @return static
		 */
		public function setSuffix($suffix)
		{
			$this->suffix = $suffix;
			return $this;
		}


		/**
		 * @param  int
		 * @return static
		 */
		public function setDecimals($decimals)
		{
			$this->decimals = $decimals;
			return $this;
		}


		/**
		 * @param  mixed
		 * @param  object|array
		 * @return string
		 */
		protected function processDefaultFormat($value, $row)
		{
			if (is_int($value) || is_float($value)) {
				return $this->prefix . number_format($value, $this->decimals, ',', '') . $this->suffix;
			}
			return (string) $value;
		}
	}

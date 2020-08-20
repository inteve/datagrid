<?php

	namespace Inteve\DataGrid\Columns;

	use CzProject\Assert\Assert;


	class DateTimeColumn extends AbstractColumn
	{
		/** @var string */
		private $format;


		/**
		 * @param  string
		 * @param  string
		 * @param  string
		 * @param  string|NULL
		 */
		public function __construct($name, $label, $format, $rowField = NULL)
		{
			parent::__construct($name, $label, $rowField);
			Assert::string($format);
			$this->format = $format;
		}


		/**
		 * @param  mixed
		 * @return string|Html
		 */
		protected function processDefaultFormat($value, $row)
		{
			if (!($value instanceof \DateTimeInterface) && is_string($value)) {
				$value = new \DateTimeImmutable($value);
			}

			if ($value instanceof \DateTimeInterface) {
				return $value->format($this->format);
			}

			return $value;
		}
	}

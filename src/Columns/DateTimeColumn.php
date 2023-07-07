<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid\Columns;

	use CzProject\Assert\Assert;


	class DateTimeColumn extends AbstractColumn
	{
		/** @var string */
		private $format;


		/**
		 * @param  string $name
		 * @param  string $label
		 * @param  string $format
		 * @param  string|NULL $rowField
		 */
		public function __construct($name, $label, $format, $rowField = NULL)
		{
			parent::__construct($name, $label, $rowField);
			Assert::string($format);
			$this->format = $format;
		}


		protected function processDefaultFormat($value, $row)
		{
			if (!($value instanceof \DateTimeInterface) && is_string($value)) {
				$value = new \DateTimeImmutable($value);
			}

			if ($value instanceof \DateTimeInterface) {
				return $value->format($this->format);
			}

			if (is_scalar($value) || $value === NULL || (is_object($value) && method_exists($value, '__toString'))) {
				return (string) $value;
			}

			throw new \Inteve\DataGrid\InvalidArgumentException("Value type '" . gettype($value) . "' is not accepted.");
		}
	}

<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class FilterCondition
	{
		use \Nette\SmartObject;

		const EQUAL = 0;
		const LIKE = 1;

		/** @var string */
		private $field;

		/** @var int */
		private $comparison;

		/** @var mixed */
		private $value;

		/** @var FilterModifier[] */
		private $modifiers = [];


		/**
		 * @param  string $field
		 * @param  int $comparison
		 * @param  mixed $value
		 */
		public function __construct($field, $comparison, $value)
		{
			Assert::string($field);
			Assert::int($comparison);
			$this->field = $field;
			$this->comparison = $comparison;
			$this->value = $value;
		}


		/**
		 * @return string
		 */
		public function getField()
		{
			return $this->field;
		}


		/**
		 * @return int
		 */
		public function getComparison()
		{
			return $this->comparison;
		}


		/**
		 * @return mixed
		 */
		public function getValue()
		{
			return $this->value;
		}


		/**
		 * @param  string $modifier
		 * @param  array<string, mixed> $arguments
		 * @return $this
		 */
		public function addModifier($modifier, array $arguments = [])
		{
			$this->modifiers[] = new FilterModifier($modifier, $arguments);
			return $this;
		}


		/**
		 * @return FilterModifier[]
		 */
		public function getModifiers()
		{
			return $this->modifiers;
		}


		/**
		 * @param  string $field
		 * @param  int $comparison
		 * @param  mixed $value
		 * @return self
		 */
		public static function create($field, $comparison, $value)
		{
			return new self($field, $comparison, $value);
		}
	}

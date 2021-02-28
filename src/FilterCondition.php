<?php

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

		/** @var FilterModifier */
		private $modifiers = [];


		/**
		 * @param  string
		 * @param  int
		 * @param  mixed
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
		 * @param  string
		 * @param  array
		 * @return self
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
		 * @param  string
		 * @param  int
		 * @param  mixed
		 * @return static
		 */
		public static function create($field, $comparison, $value)
		{
			return new static($field, $comparison, $value);
		}
	}

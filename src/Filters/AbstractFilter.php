<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid\Filters;

	use CzProject\Assert\Assert;
	use Inteve\DataGrid\FilterCondition;
	use Inteve\DataGrid\IDataSource;
	use Inteve\DataGrid\IFilter;
	use Nette\Utils\Strings;


	abstract class AbstractFilter implements IFilter
	{
		use \Nette\SmartObject;

		/** @var string */
		protected $name;

		/** @var string */
		protected $label;

		/** @var string */
		protected $rowField;

		/** @var string|string[]|NULL */
		protected $value;

		/** @var IFilter::*|callable(string $rowField, string|string[]|NULL $value):(FilterCondition|FilterCondition[]) */
		protected $condition = IFilter::EQUAL;


		/**
		 * @param  string $name
		 * @param  string $label
		 * @param  string|NULL $rowField
		 */
		public function __construct($name, $label, $rowField = NULL)
		{
			Assert::string($name);
			Assert::string($label);
			Assert::stringOrNull($rowField);

			$this->name = $name;
			$this->rowField = $rowField !== NULL ? $rowField : $name;
			$this->label = $label;
		}


		/**
		 * @return string
		 */
		public function getName()
		{
			return $this->name;
		}


		/**
		 * @return string
		 */
		public function getLabel()
		{
			return $this->label;
		}


		/**
		 * @return bool
		 */
		public function isActive()
		{
			return $this->value !== NULL;
		}


		/**
		 * @param  string $rowField
		 * @return $this
		 */
		public function setRowField($rowField)
		{
			Assert::string($rowField);
			$this->rowField = $rowField;
			return $this;
		}


		/**
		 * @return string
		 */
		public function getRowField()
		{
			return $this->rowField;
		}


		/**
		 * @param  string $value
		 * @return $this
		 */
		public function setValue($value)
		{
			$value = Strings::trim($value);
			$this->value = $value !== '' ? $value : NULL;
			return $this;
		}


		public function getValue()
		{
			if (is_array($this->value)) {
				return implode(',', $this->value);
			}

			return $this->value;
		}


		/**
		 * @param  callable(string $rowField, string|string[]|NULL $value):(FilterCondition|FilterCondition[])|IFilter::* $condition
		 * @return $this
		 */
		public function setCondition($condition)
		{
			$this->condition = $condition;
			return $this;
		}


		/**
		 * @return FilterCondition|FilterCondition[]
		 */
		public function prepareCondition()
		{
			if (is_callable($this->condition, TRUE)) {
				return call_user_func($this->condition, $this->rowField, $this->value);
			}

			return new FilterCondition($this->rowField, $this->condition, $this->value);
		}
	}

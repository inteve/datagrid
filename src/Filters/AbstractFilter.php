<?php

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

		/** @var mixed|NULL */
		protected $value;

		/** @var int|callback */
		protected $condition = IFilter::EQUAL;


		/**
		 * @param  string
		 * @param  string
		 * @param  string|NULL
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
		 * @param  string
		 * @return self
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
		 * @param  mixed
		 * @return self
		 */
		public function setValue($value)
		{
			$value = Strings::trim($value);
			$this->value = $value !== '' ? $value : NULL;
			return $this;
		}


		/**
		 * @return string|NULL
		 */
		public function getValue()
		{
			return $this->value;
		}


		/**
		 * @param  callback|int
		 * @return self
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

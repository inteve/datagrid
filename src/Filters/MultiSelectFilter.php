<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid\Filters;

	use Inteve\DataGrid\IFilter;
	use Nette\Forms\Controls\MultiSelectBox;


	class MultiSelectFilter extends AbstractFilter
	{
		/** @var array<string|int, mixed> */
		protected $items;


		/**
		 * @param  string $name
		 * @param  string $label
		 * @param  array<string|int, mixed> $items
		 * @param  string|NULL $rowField
		 */
		public function __construct($name, $label, array $items, $rowField = NULL)
		{
			parent::__construct($name, $label, $rowField);
			$this->items = $items;
		}


		/**
		 * @param  string $value
		 * @return self
		 */
		public function setValue($value)
		{
			if ($value === '') {
				$this->value = NULL;
				return $this;
			}

			$values = explode(',', $value);
			array_walk($values, function (string $value, $key) {
				return \Nette\Utils\Strings::trim($value);
			});
			array_filter($values, function ($value) {
				return $value !== '';
			});
			$this->value = $values;
			return $this;
		}


		/**
		 * @return string|NULL
		 */
		public function getValue()
		{
			if ($this->value === NULL) {
				return NULL;
			}

			if (is_string($this->value)) {
				return $this->value;
			}

			return implode(',', $this->value);
		}


		/**
		 * @param  string|string[] $value
		 * @return self
		 */
		public function setFormValue($value)
		{
			$this->value = (array) $value;

			if (empty($this->value)) {
				$this->value = NULL;
			}

			return $this;
		}


		public function getFormControl()
		{
			$select = new MultiSelectBox;
			$select->setItems($this->items);
			$select->setDefaultValue($this->value);
			return $select;
		}
	}

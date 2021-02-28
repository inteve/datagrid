<?php

	namespace Inteve\DataGrid\Filters;

	use Inteve\DataGrid\IFilter;
	use Nette\Forms\Controls\MultiSelectBox;


	class MultiSelectFilter extends AbstractFilter
	{
		/** @var array */
		protected $items;


		/**
		 * @param  string
		 * @param  string
		 * @param  array
		 * @param  string|NULL
		 */
		public function __construct($name, $label, array $items, $rowField = NULL)
		{
			parent::__construct($name, $label, $rowField);
			$this->items = $items;
		}


		/**
		 * @param  string
		 * @return self
		 */
		public function setValue($value)
		{
			if ($value === '') {
				$this->value = NULL;
				return $this;
			}

			$values = explode(',', $value);
			array_walk($values, ['Nette\Utils\Strings', 'trim']);
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

			return implode(',', $this->value);
		}


		/**
		 * @param  mixed
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


		/**
		 * @return IControl
		 */
		public function getFormControl()
		{
			$select = new MultiSelectBox;
			$select->setItems($this->items);
			$select->setDefaultValue($this->value);
			return $select;
		}
	}

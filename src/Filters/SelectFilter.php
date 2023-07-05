<?php

	namespace Inteve\DataGrid\Filters;

	use Inteve\DataGrid\IFilter;
	use Nette\Forms\Controls\SelectBox;


	class SelectFilter extends AbstractFilter
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
		public function setFormValue($value)
		{
			$this->setValue($value);
			return $this;
		}


		public function getFormControl()
		{
			$select = new SelectBox;
			$select->setItems($this->items);
			$select->setPrompt('--');
			$select->setDefaultValue($this->value);
			return $select;
		}
	}

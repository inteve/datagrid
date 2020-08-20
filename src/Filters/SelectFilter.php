<?php

	namespace Inteve\DataGrid\Filters;

	use Inteve\DataGrid\IFilter;
	use Nette\Forms\Controls\SelectBox;


	class SelectFilter extends AbstractFilter
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
		 * @param  mixed
		 * @return self
		 */
		public function setFormValue($value)
		{
			$this->setValue($value);
		}


		/**
		 * @return IControl
		 */
		public function getFormControl()
		{
			$select = new SelectBox;
			$select->setItems($this->items);
			$select->setPrompt('--');
			$select->setDefaultValue($this->value);
			return $select;
		}
	}

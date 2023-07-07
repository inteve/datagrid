<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid\Filters;

	use Inteve\DataGrid\IFilter;
	use Nette\Forms\Controls\TextInput;


	class TextFilter extends AbstractFilter
	{
		/** @var string|NULL */
		protected $placeholder;


		/**
		 * @param  string|NULL $placeholder
		 * @return static
		 */
		public function setPlaceholder($placeholder)
		{
			$this->placeholder = $placeholder;
			return $this;
		}


		/**
		 * @param  string $value
		 * @return static
		 */
		public function setFormValue($value)
		{
			$this->setValue($value);
			return $this;
		}


		public function getFormControl()
		{
			$input = new TextInput;
			$input->setDefaultValue($this->value);

			if (isset($this->placeholder)) {
				$input->setAttribute('placeholder', $this->placeholder);
			}

			return $input;
		}
	}

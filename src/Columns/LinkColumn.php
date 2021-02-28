<?php

	namespace Inteve\DataGrid\Columns;

	use Nette\Utils\Html;
	use Nette\Utils\Validators;


	class LinkColumn extends AbstractColumn
	{
		/** @var callable|NULL */
		private $linkFactory;


		/**
		 * @param  string
		 * @param  string
		 * @param  callable|NULL
		 * @param  string|NULL
		 */
		public function __construct($name, $label, callable $linkFactory = NULL, $rowField = NULL)
		{
			parent::__construct($name, $label, $rowField);
			$this->linkFactory = $linkFactory;
		}


		/**
		 * @param  mixed
		 * @return string|Html
		 */
		protected function processDefaultFormat($value, $row)
		{
			$url = $value;
			$target = NULL;

			if ($this->linkFactory !== NULL) {
				$url = call_user_func($this->linkFactory, $value, $row);
			}

			if (Validators::isUrl($url)) { // external URL
				$target = '_blank';
			}

			return Html::el('a', [
				'href' => $url,
				'target' => $target,
			])->setText($value);
		}
	}

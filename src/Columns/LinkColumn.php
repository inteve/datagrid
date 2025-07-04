<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid\Columns;

	use Nette\Utils\Html;
	use Nette\Utils\Validators;


	class LinkColumn extends AbstractColumn
	{
		/** @var callable|NULL */
		private $linkFactory;


		/**
		 * @param  string $name
		 * @param  string $label
		 * @param  callable|NULL $linkFactory
		 * @param  string|NULL $rowField
		 */
		public function __construct($name, $label, ?callable $linkFactory = NULL, $rowField = NULL)
		{
			parent::__construct($name, $label, $rowField);
			$this->linkFactory = $linkFactory;
		}


		protected function processDefaultFormat($value, $row)
		{
			$url = $value;
			$target = NULL;

			if ($this->linkFactory !== NULL) {
				$url = call_user_func($this->linkFactory, $value, $row);
			}

			if (is_string($url) && Validators::isUrl($url)) { // external URL
				$target = '_blank';
			}

			return Html::el('a', [
				'href' => $url,
				'target' => $target,
			])->setText($value);
		}
	}

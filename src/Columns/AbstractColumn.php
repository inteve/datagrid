<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid\Columns;

	use CzProject\Assert\Assert;
	use Inteve\DataGrid\Helpers;
	use Inteve\DataGrid\IColumn;


	abstract class AbstractColumn implements IColumn
	{
		use \Nette\SmartObject;

		/** @var string */
		protected $name;

		/** @var string */
		protected $label;

		/** @var string */
		protected $rowField;

		/** @var callable(array<string, mixed>|object $row):(string|\Nette\Utils\Html)|NULL  */
		protected $customRender;

		/** @var callable|NULL */
		protected $valueProvider;

		/** @var string|NULL */
		protected $sort;

		/** @var bool|string[] */
		protected $sortable = FALSE;

		/** @var array<string, mixed>|NULL  [attr => value] */
		protected $attributes;

		/** @var array<string, mixed>|NULL  [property => value] */
		protected $styles;


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


		public function formatValue($row)
		{
			if ($this->customRender !== NULL) {
				return call_user_func($this->customRender, $row);
			}

			$value = NULL;

			if ($this->valueProvider !== NULL) {
				$value = call_user_func($this->valueProvider, $row);

			} else {
				$value = Helpers::getRowValue($row, $this->getRowField());
			}

			return $this->processDefaultFormat($value, $row);
		}


		/**
		 * @param  callable(array<string, mixed>|object $row):(string|\Nette\Utils\Html)|NULL $customRender
		 * @return $this
		 */
		public function setCustomRender($customRender)
		{
			$this->customRender = $customRender;
			return $this;
		}


		/**
		 * @param  callable|NULL $valueProvider
		 * @return $this
		 */
		public function setValueProvider(?callable $valueProvider = NULL)
		{
			$this->valueProvider = $valueProvider;
			return $this;
		}


		/**
		 * @return string|NULL
		 */
		public function getSort()
		{
			Assert::assert($this->isSortable(), 'Column is not sortable.');
			return $this->sort;
		}


		/**
		 * @param  string $sorting
		 * @return $this
		 */
		public function setSort($sorting)
		{
			Assert::assert($this->isSortable(), 'Column is not sortable.');

			if ($sorting !== IColumn::ASC && $sorting !== IColumn::DESC) {
				throw new \Inteve\DataGrid\InvalidArgumentException("Invalid sorting '$sorting'.");
			}

			$this->sort = $sorting;
			return $this;
		}


		/**
		 * @param  bool|string|string[] $sortable
		 * @return $this
		 */
		public function setSortable($sortable = TRUE)
		{
			if (!is_bool($sortable) && !is_array($sortable)) {
				$sortable = [$sortable];
			}
			$this->sortable = $sortable;
			return $this;
		}


		/**
		 * @return string[]|FALSE
		 */
		public function getSortableFields()
		{
			if ($this->sortable === FALSE) {
				return FALSE;

			} elseif ($this->sortable === TRUE) {
				return [$this->getRowField()];
			}

			return $this->sortable;
		}


		/**
		 * @return bool
		 */
		public function isSortable()
		{
			return $this->sortable !== FALSE;
		}


		/**
		 * @param  array<string, mixed>|NULL $defaultAttributes
		 * @return array<string, mixed>|NULL
		 */
		public function getAttributes(?array $defaultAttributes = NULL)
		{
			$attrs = $this->attributes;

			if ($this->styles !== NULL) {
				$attrs['style'] = $this->styles;
			}

			return Helpers::mergeAttributes($attrs, $defaultAttributes);
		}


		/**
		 * @param  string $attr
		 * @param  scalar|NULL $value
		 * @return $this
		 */
		public function setAttribute($attr, $value)
		{
			if ($attr === 'style') {
				throw new \Inteve\DataGrid\InvalidArgumentException('Use $grid->setStyle() for setting of styles.');
			}

			if ($value === NULL) {
				unset($this->attributes[$attr]);

			} else {
				$this->attributes[$attr] = $value;
			}

			return $this;
		}


		/**
		 * @param  string $property
		 * @param  scalar|NULL $value
		 * @return $this
		 */
		public function setStyle($property, $value)
		{
			if ($value === NULL) {
				unset($this->styles[$property]);

			} else {
				$this->styles[$property] = $value;
			}

			return $this;
		}


		/**
		 * @param  mixed $value
		 * @param  object|array<string, mixed> $row
		 * @return string|\Nette\Utils\Html
		 */
		abstract protected function processDefaultFormat($value, $row);
	}

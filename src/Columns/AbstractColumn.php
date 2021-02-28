<?php

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

		/** @var callback|NULL */
		protected $customRender;

		/** @var callback|NULL */
		protected $valueProvider;

		/** @var string|NULL */
		protected $sort;

		/** @var bool|string[] */
		protected $sortable = FALSE;

		/** @var array|NULL  [attr => value] */
		protected $attributes;

		/** @var array|NULL  [property => value] */
		protected $styles;


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
		 * @param  object|array
		 * @return string
		 */
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
		 * @param  callback|NULL
		 * @return self
		 */
		public function setCustomRender($customRender)
		{
			$this->customRender = $customRender;
			return $this;
		}


		/**
		 * @param  callback|NULL
		 * @return self
		 */
		public function setValueProvider(callable $valueProvider = NULL)
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
		 * @param  string
		 * @return self
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
		 * @param  bool|string|string[]
		 * @return self
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
		 * @param  array|NULL
		 * @return array|NULL
		 */
		public function getAttributes(array $defaultAttributes = NULL)
		{
			$attrs = $this->attributes;

			if ($this->styles !== NULL) {
				$attrs['style'] = $this->styles;
			}

			return Helpers::mergeAttributes($attrs, $defaultAttributes);
		}


		/**
		 * @param  string
		 * @param  scalar|NULL
		 * @return self
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
		 * @param  string
		 * @param  scalar|NULL
		 * @return self
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
		 * @param  mixed
		 * @param  object|array
		 * @return string
		 */
		abstract protected function processDefaultFormat($value, $row);
	}

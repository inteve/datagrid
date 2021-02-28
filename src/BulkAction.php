<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class BulkAction
	{
		use \Nette\SmartObject;

		const SELECT_NONE = 0;
		const SELECT_PAGE = 1;
		const SELECT_ALL = 2;

		/** @var string */
		private $name;

		/** @var string */
		private $label;

		/** @var callback */
		private $callback;

		/** @var int */
		private $emptySelection;

		/** @var array */
		private $options;


		/**
		 * @param  string
		 * @param  string
		 * @param  callback
		 * @param  int  see  self::SELECT_*
		 */
		public function __construct($name, $label, $callback, $emptySelection = self::SELECT_NONE)
		{
			Assert::string($name);
			Assert::string($label);
			Assert::in($emptySelection, [
				self::SELECT_NONE,
				self::SELECT_PAGE,
				self::SELECT_ALL,
			]);
			$this->name = $name;
			$this->label = $label;
			$this->callback = $callback;
			$this->emptySelection = $emptySelection;
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
		 * @return callback
		 */
		public function getCallback()
		{
			return $this->callback;
		}


		/**
		 * @return int
		 */
		public function getEmptySelection()
		{
			return $this->emptySelection;
		}


		/**
		 * @param  string
		 * @param  mixed
		 * @return self
		 */
		public function setOption($name, $value)
		{
			$this->options[$name] = $value;
			return $this;
		}


		/**
		 * @param  string
		 * @param  mixed|NULL
		 * @return mixed|NULL
		 */
		public function getOption($name, $default = NULL)
		{
			return isset($this->options[$name]) ? $this->options[$name] : $default;
		}
	}

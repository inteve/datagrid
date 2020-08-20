<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class Action
	{
		use \Nette\SmartObject;

		/** @var string */
		private $name;

		/** @var string */
		private $label;

		/** @var mixed */
		private $linkFactory;

		/** @var IDataSource */
		private $dataSource;

		/** @var string|NULL */
		private $icon;

		/** @var string */
		private $primaryKey = 'id';

		/** @var callback */
		private $disabled;


		/**
		 * @param  string
		 * @param  string
		 * @param  mixed
		 */
		public function __construct($name, $label, $linkFactory, IDataSource $dataSource)
		{
			Assert::string($name);
			Assert::string($label);
			LinkHelper::checkFactory($linkFactory);
			$this->name = $name;
			$this->label = $label;
			$this->linkFactory = $linkFactory;
			$this->dataSource = $dataSource;
		}


		/**
		 * @return string
		 */
		public function getLabel()
		{
			return $this->label;
		}


		/**
		 * @param  object|array
		 * @return string
		 */
		public function getUrl($row)
		{
			return LinkHelper::createUrl($this->linkFactory, $this->primaryKey, $this->dataSource->getRowId($row), $row);
		}


		/**
		 * @param  string|NULL
		 * @return self
		 */
		public function setIcon($icon)
		{
			Assert::stringOrNull($icon);
			$this->icon = $icon;
			return $this;
		}


		/**
		 * @return string|NULL
		 */
		public function getIcon()
		{
			return $this->icon;
		}


		/**
		 * @param  string
		 * @return self
		 */
		public function setPrimaryKey($primaryKey)
		{
			Assert::string($primaryKey);
			$this->primaryKey = $primaryKey;
			return $this;
		}


		/**
		 * @return string
		 */
		public function getPrimaryKey()
		{
			return $this->primaryKey;
		}


		/**
		 * @param  callback
		 * @return self
		 */
		public function setDisabled($callback)
		{
			$this->disabled = $callback;
			return $this;
		}


		/**
		 * @param  array|object
		 * @return bool
		 */
		public function isDisabled($row)
		{
			if ($this->disabled === NULL) {
				return FALSE;
			}

			return call_user_func($this->disabled, $row);
		}
	}

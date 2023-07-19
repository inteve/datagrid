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

		/** @var callable|\Nette\Application\UI\Link */
		private $linkFactory;

		/** @var IDataSource */
		private $dataSource;

		/** @var string|NULL */
		private $icon;

		/** @var string */
		private $primaryKey = 'id';

		/** @var callable */
		private $disabled;

		/** @var string */
		private $type;


		/**
		 * @param  string $name
		 * @param  string $label
		 * @param  callable|\Nette\Application\UI\Link $linkFactory
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
			$this->type = $name === 'delete' ? 'delete' : 'default';
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
		 * @param  object|array<string, mixed> $row
		 * @return string
		 */
		public function getUrl($row)
		{
			return LinkHelper::createUrl($this->linkFactory, $this->primaryKey, $this->dataSource->getRowId($row), $row);
		}


		/**
		 * @param  string|NULL $icon
		 * @return $this
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
		 * @param  string $primaryKey
		 * @return $this
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
		 * @return $this
		 */
		public function setDisabled(callable $callback)
		{
			$this->disabled = $callback;
			return $this;
		}


		/**
		 * @param  array<string, mixed>|object $row
		 * @return bool
		 */
		public function isDisabled($row)
		{
			if ($this->disabled === NULL) {
				return FALSE;
			}

			return call_user_func($this->disabled, $row);
		}


		/**
		 * @param  string $type
		 * @return $this
		 */
		public function setType($type)
		{
			$this->type = $type;
			return $this;
		}


		/**
		 * @param  string $type
		 * @return bool
		 */
		public function isOfType($type)
		{
			return $this->type === $type;
		}
	}

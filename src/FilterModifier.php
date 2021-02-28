<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class FilterModifier
	{
		use \Nette\SmartObject;

		/** @var string */
		private $name;

		/** @var array */
		private $arguments;


		/**
		 * @param  string
		 */
		public function __construct($name, array $arguments = [])
		{
			Assert::string($name);
			$this->name = $name;
			$this->arguments = $arguments;
		}


		/**
		 * @return string
		 */
		public function getName()
		{
			return $this->name;
		}


		/**
		 * @return array
		 */
		public function getArguments()
		{
			return $this->arguments;
		}
	}

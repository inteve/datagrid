<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class FilterModifier
	{
		use \Nette\SmartObject;

		/** @var string */
		private $name;

		/** @var array<string, mixed> */
		private $arguments;


		/**
		 * @param  string $name
		 * @param  array<string, mixed> $arguments
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
		 * @return array<string, mixed>
		 */
		public function getArguments()
		{
			return $this->arguments;
		}
	}

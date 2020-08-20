<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class DataSourceResult
	{
		use \Nette\SmartObject;

		/** @var array */
		private $rows;

		/** @var int */
		private $count;


		/**
		 * @param  array
		 * @param  int
		 */
		public function __construct(array $rows, $count)
		{
			$this->rows = $rows;
			$this->count = $count;
		}


		/**
		 * @return array
		 */
		public function getRows()
		{
			return $this->rows;
		}


		/**
		 * @return int
		 */
		public function getCount()
		{
			return $this->count;
		}
	}

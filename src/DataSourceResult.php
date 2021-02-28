<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class DataSourceResult
	{
		use \Nette\SmartObject;

		/** @var array */
		private $rows;

		/** @var int */
		private $totalCount;


		/**
		 * @param  array
		 * @param  int
		 */
		public function __construct(array $rows, $totalCount)
		{
			$this->rows = $rows;
			$this->totalCount = $totalCount;
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
		public function getTotalCount()
		{
			return $this->totalCount;
		}
	}

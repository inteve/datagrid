<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class DataSourceResult
	{
		use \Nette\SmartObject;

		/** @var array<array<string, mixed>|object> */
		private $rows;

		/** @var int */
		private $totalCount;


		/**
		 * @param  array<array<string, mixed>|object> $rows
		 * @param  int $totalCount
		 */
		public function __construct(array $rows, $totalCount)
		{
			$this->rows = $rows;
			$this->totalCount = $totalCount;
		}


		/**
		 * @return array<array<string, mixed>|object>
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

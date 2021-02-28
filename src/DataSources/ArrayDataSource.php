<?php

	namespace Inteve\DataGrid\DataSources;

	use CzProject\Assert\Assert;
	use Inteve\DataGrid\DataSourceResult;
	use Inteve\DataGrid\IColumn;
	use Inteve\DataGrid\IDataSource;
	use Inteve\DataGrid\IFilter;


	class ArrayDataSource implements IDataSource
	{
		use \Nette\SmartObject;

		/** @var array */
		private $rows;

		/** @var string */
		private $idKey;


		public function __construct(array $rows, $idKey = 'id')
		{
			$this->rows = $rows;
			$this->idKey = $idKey;
		}


		/**
		 * @param  array|object
		 * @return scalar
		 */
		public function getRowId($row)
		{
			if (!isset($row[$this->idKey])) {
				throw new \Inteve\DataGrid\InvalidArgumentException('Missing ID key in row.');
			}

			return $row[$this->idKey];
		}


		/**
		 * @param  IColumn[]
		 * @param  IFilter[]
		 * @param  int|NULL
		 * @param  int|NULL
		 * @return DataSourceResult
		 */
		public function getData(array $columns, array $filters, array $sorts, $page, $itemsOnPage)
		{
			Assert::intOrNull($page);
			Assert::intOrNull($itemsOnPage);

			if (!empty($filters)) {
				throw new \Inteve\DataGrid\Exception('Not implemented yet.');
			}

			if (!empty($sorts)) {
				throw new \Inteve\DataGrid\Exception('Not implemented yet.');
			}

			// pager
			$data = NULL;

			if ($page !== NULL && $itemsOnPage !== NULL) { // offset + limit
				$data = array_slice($this->rows, ($page - 1) * $itemsOnPage, $itemsOnPage, FALSE /*preserve keys*/);

			} elseif ($page === NULL && $itemsOnPage !== NULL) { // only limit
				$data = array_slice($this->rows, 0, $itemsOnPage, FALSE /*preserve keys*/);

			} elseif ($page === NULL && $itemsOnPage === NULL) { // all
				$data = $this->rows;

			} else { // only offset
				throw new \Inteve\DataGrid\Exception('Invalid pagination.');
			}

			return new DataSourceResult($data, count($this->rows));
		}
	}

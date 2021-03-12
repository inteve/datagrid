<?php

	namespace Inteve\DataGrid\DataSources;

	use CzProject\Assert\Assert;
	use Inteve\DataGrid\DataPaging;
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
			if (is_object($row)) {
				if (!isset($row->{$this->idKey})) {
					throw new \Inteve\DataGrid\InvalidArgumentException('Missing ID key in row.');
				}

				return $row->{$this->idKey};
			}

			if (!isset($row[$this->idKey])) {
				throw new \Inteve\DataGrid\InvalidArgumentException('Missing ID key in row.');
			}

			return $row[$this->idKey];
		}


		public function getData(array $columns, array $filters, array $sorts, DataPaging $paging)
		{
			if (!empty($filters)) {
				throw new \Inteve\DataGrid\Exception('Not implemented yet.');
			}

			if (!empty($sorts)) {
				throw new \Inteve\DataGrid\Exception('Not implemented yet.');
			}

			// pager
			$data = NULL;

			if ($paging->hasOffset() && $paging->hasLimit()) { // offset + limit
				$data = array_slice($this->rows, $paging->getOffset(), $paging->getLimit(), FALSE /*preserve keys*/);

			} elseif (!$paging->hasOffset() && $paging->hasLimit()) { // only limit
				$data = array_slice($this->rows, 0, $paging->getLimit(), FALSE /*preserve keys*/);

			} elseif (!$paging->hasOffset() && !$paging->hasLimit()) { // all
				$data = $this->rows;

			} else { // only offset
				$data = array_slice($this->rows, $paging->getOffset(), NULL, FALSE /*preserve keys*/);
			}

			return new DataSourceResult($data, count($this->rows));
		}
	}

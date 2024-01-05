<?php

	namespace Inteve\DataGrid\DataSources;

	use CzProject\Assert\Assert;
	use Inteve\DataGrid\DataPaging;
	use Inteve\DataGrid\DataSourceResult;
	use Inteve\DataGrid\FilterCondition;
	use Inteve\DataGrid\IColumn;
	use Inteve\DataGrid\IDataSource;
	use Inteve\DataGrid\IFilter;
	use Nette\Utils\Strings;


	class ArrayDataSource implements IDataSource
	{
		use \Nette\SmartObject;

		/** @var array<array<string, mixed>|object> */
		private $rows;

		/** @var string */
		private $idKey;


		/**
		 * @param array<array<string, mixed>|object> $rows
		 * @param string $idKey
		 */
		public function __construct(array $rows, $idKey = 'id')
		{
			$this->rows = $rows;
			$this->idKey = $idKey;
		}


		public function getRowId($row)
		{
			if (is_object($row)) {
				if (!isset($row->{$this->idKey})) {
					throw new \Inteve\DataGrid\InvalidArgumentException('Missing ID key in row.');
				}

				if (!is_scalar($row->{$this->idKey})) {
					throw new \Inteve\DataGrid\InvalidArgumentException('ID in row must be scalar.');
				}

				return $row->{$this->idKey};
			}

			if (!isset($row[$this->idKey])) {
				throw new \Inteve\DataGrid\InvalidArgumentException('Missing ID key in row.');
			}

			if (!is_scalar($row[$this->idKey])) {
				throw new \Inteve\DataGrid\InvalidArgumentException('ID in row must be scalar.');
			}

			return $row[$this->idKey];
		}


		public function getData(array $columns, array $filters, array $sorts, DataPaging $paging)
		{
			if (!empty($sorts)) {
				throw new \Inteve\DataGrid\Exception('Not implemented yet.');
			}

			$data = $this->rows;

			// filtering
			foreach ($filters as $filter) {
				if (!$filter->isActive()) {
					continue;
				}

				$data = $this->filterData($data, $filter->prepareCondition());
			}

			$totalCount = count($data);

			// pager
			if ($paging->hasOffset() && $paging->hasLimit()) { // offset + limit
				$data = array_slice($data, $paging->getOffset(), $paging->getLimit(), FALSE /*preserve keys*/);

			} elseif (!$paging->hasOffset() && $paging->hasLimit()) { // only limit
				$data = array_slice($data, 0, $paging->getLimit(), FALSE /*preserve keys*/);

			} elseif (!$paging->hasOffset() && !$paging->hasLimit()) { // all
				$data = $data;

			} else { // only offset
				$data = array_slice($data, $paging->getOffset(), NULL, FALSE /*preserve keys*/);
			}

			return new DataSourceResult($data, $totalCount);
		}


		/**
		 * @param  array<array<string, mixed>|object> $rows
		 * @param  FilterCondition|FilterCondition[] $conditions
		 * @return array<array<string, mixed>|object>
		 */
		protected function filterData(array $rows, $conditions)
		{
			if (!is_array($conditions)) {
				$conditions = [$conditions];
			}

			foreach ($conditions as $condition) {
				if (count($condition->getModifiers()) > 0) {
					throw new \Inteve\DataGrid\Exception('Modifiers are not implemented yet.');
				}
			}

			$matchedRows = [];

			foreach ($rows as $row) {
				$hasMatch = FALSE;

				foreach ($conditions as $condition) {
					$rowValue = $this->fetchValue($row, $condition->getField());
					$filterValue = $condition->getValue();
					$comparison = $condition->getComparison();

					if ($comparison === IFilter::EQUAL) {
						if (is_null($filterValue)) {
							$hasMatch = $hasMatch || ($rowValue === NULL);

						} elseif (is_array($filterValue)) {
							$hasMatch = $hasMatch || in_array($rowValue, $filterValue, TRUE);

						} else {
							$hasMatch = $hasMatch || ($rowValue === $filterValue);
						}

					} elseif ($comparison === IFilter::LIKE) {
						if (is_scalar($rowValue) && is_scalar($filterValue)) {
							$hasMatch = $hasMatch || Strings::contains(
								Strings::lower((string) $rowValue),
								Strings::lower((string) $filterValue)
							);
						}

					} else {
						throw new \Inteve\DataGrid\InvalidArgumentException("Unknow comparison '$comparison'.");
					}
				}

				if ($hasMatch) {
					$matchedRows[] = $row;
				}
			}

			return $matchedRows;
		}


		/**
		 * @param  array<string, mixed>|object $row
		 * @param  string $column
		 * @return mixed
		 */
		protected function fetchValue($row, $column)
		{
			if (is_object($row)) {
				return $row->{$column};
			}

			if (!array_key_exists($column, $row)) {
				throw new \Inteve\DataGrid\InvalidArgumentException("Missing column '$column' in row.
					");
			}

			return $row[$column];
		}
	}

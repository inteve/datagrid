<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid\DataSources;

	use CzProject\Assert\Assert;
	use Inteve\DataGrid\DataPaging;
	use Inteve\DataGrid\DataSourceResult;
	use Inteve\DataGrid\IColumn;
	use Inteve\DataGrid\IDataSource;
	use Inteve\DataGrid\IFilter;


	class MergedDataSource implements IDataSource
	{
		use \Nette\SmartObject;

		/** @var array<class-string, IDataSource> */
		private $dataSources;


		/**
		 * @param array<class-string, IDataSource> $dataSources
		 */
		public function __construct(array $dataSources)
		{
			$this->dataSources = $dataSources;
		}


		public function getRowId($row)
		{
			if (is_array($row)) {
				throw new \Inteve\DataGrid\InvalidArgumentException(self::class . ' requires only objects, array given.');
			}
			$class = get_class($row);

			if (!isset($this->dataSources[$class])) {
				throw new \Inteve\DataGrid\InvalidArgumentException('Unknow row type, there is no data source for it.');
			}

			return $this->dataSources[$class]->getRowId($row);
		}


		public function getData(array $columns, array $filters, array $sorts, DataPaging $paging)
		{
			if ($paging->withoutPaging()) { // all data
				$rows = [];
				$count = 0;

				foreach ($this->dataSources as $dataSource) {
					$result = $dataSource->getData($columns, $filters, $sorts, $paging);
					$count += $result->getTotalCount();

					foreach ($result->getRows() as $row) {
						$rows[] = $row;
					}
				}

				return new DataSourceResult($rows, $count);
			}

			if ($paging->onlyLimit()) {
				$rows = [];
				$count = 0;

				foreach ($this->dataSources as $dataSource) {
					$result = $dataSource->getData($columns, $filters, $sorts, $paging);
					$count += $result->getTotalCount();
					$dataSourceRows = $result->getRows();

					foreach ($dataSourceRows as $row) {
						$rows[] = $row;
					}

					$paging = new DataPaging(NULL, max(0, $paging->getLimit() - count($dataSourceRows)));
				}

				return new DataSourceResult($rows, $count);
			}

			if ($paging->onlyOffset()) {
				$rows = [];
				$count = 0;
				$offset = $paging->getOffset();

				foreach ($this->dataSources as $dataSource) {
					$result = $dataSource->getData($columns, $filters, $sorts, new DataPaging($offset, NULL));
					$count += $result->getTotalCount();
					$dataSourceRows = $result->getRows();
					$offset = max(0, $offset - $result->getTotalCount());

					foreach ($dataSourceRows as $row) {
						$rows[] = $row;
					}
				}

				return new DataSourceResult($rows, $count);
			}

			// limit & offset
			$rows = [];
			$count = 0;
			$offset = $paging->getOffset();
			$limit = $paging->getLimit();

			foreach ($this->dataSources as $dataSource) {
				$result = $dataSource->getData($columns, $filters, $sorts, new DataPaging($offset, $limit));
				$count += $result->getTotalCount();
				$dataSourceRows = $result->getRows();

				foreach ($dataSourceRows as $row) {
					$rows[] = $row;
				}

				$offset = max(0, $offset - $result->getTotalCount());
				$limit = max(0, $limit - count($dataSourceRows));
			}

			return new DataSourceResult($rows, $count);
		}
	}

<?php

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


		public function __construct(array $dataSources)
		{
			$this->dataSources = $dataSources;
		}


		/**
		 * @param  array|object
		 * @return scalar
		 */
		public function getRowId($row)
		{
			$class = get_class($row);

			if (!isset($this->dataSources[$class])) {
				throw new \Inteve\DataGrid\InvalidArgumentException('Unknow row type, there is no data source for it.');
			}

			return $this->dataSources[$class]->getRowId($row);
		}


		public function getData(array $columns, array $filters, array $sorts, DataPaging $paging)
		{
			if (!$paging->hasLimit()) { // all data
				$rows = [];
				$count = 0;

				foreach ($this->dataSources as $dataSource) {
					$result = $dataSource->getData($columns, $filters, $sorts, $paging);
					$count += $result->getCount();

					foreach ($result->getRows() as $row) {
						$rows[] = $row;
					}
				}

				return new DataSourceResult($rows, $count);
			}

			throw new \Inteve\DataGrid\Exception('Not implemented yet.');
		}
	}

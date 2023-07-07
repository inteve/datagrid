<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid;


	interface IDataSource
	{
		/**
		 * @param  array<string, mixed>|object $row
		 * @return scalar
		 */
		function getRowId($row);


		/**
		 * @param  IColumn[] $columns
		 * @param  IFilter[] $filters
		 * @param  array<IColumn> $sorts
		 * @return DataSourceResult
		 */
		function getData(array $columns, array $filters, array $sorts, DataPaging $paging);
	}

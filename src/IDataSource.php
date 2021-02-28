<?php

	namespace Inteve\DataGrid;


	interface IDataSource
	{
		/**
		 * @param  array|object
		 * @return scalar
		 */
		function getRowId($row);


		/**
		 * @param  IColumn[] $columns
		 * @param  IFilter[] $filters
		 * @param  array<string, string> $sorts
		 * @return DataSourceResult
		 */
		function getData(array $columns, array $filters, array $sorts, DataPaging $paging);
	}

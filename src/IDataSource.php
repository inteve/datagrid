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
		 * @param  IColumn[]
		 * @param  IFilter[]
		 * @param  IColumn[]
		 * @param  int|NULL
		 * @param  int|NULL
		 * @return DataSourceResult
		 */
		function getData(array $columns, array $filters, array $sorts, $page, $itemsOnPage);
	}

<?php

	namespace Inteve\DataGrid;


	interface IColumn
	{
		const ASC = 'ASC';
		const DESC = 'DESC';


		/**
		 * @return string
		 */
		function getName();


		/**
		 * @return string
		 */
		function getLabel();


		/**
		 * @param  string
		 */
		function setRowField($rowField);


		/**
		 * @return string
		 */
		function getRowField();


		/**
		 * @param  object|array
		 * @return string
		 */
		function formatValue($row);


		/**
		 * @param  callback|NULL
		 */
		function setCustomRender($customRender);


		/**
		 * @param  callback|NULL
		 */
		function setValueProvider(callable $customRender = NULL);


		/**
		 * @return string|NULL
		 */
		function getSort();


		/**
		 * @param  string
		 */
		function setSort($sorting);


		/**
		 * @param  bool|string|string[]
		 */
		function setSortable($sortable = TRUE);


		/**
		 * @return string[]|FALSE
		 */
		function getSortableFields();


		/**
		 * @return bool
		 */
		function isSortable();


		/**
		 * @return array|NULL
		 */
		function getAttributes();


		/**
		 * @param  string
		 * @param  scalar|NULL
		 */
		function setAttribute($attr, $value);

		/**
		 * @param  string
		 * @param  scalar|NULL
		 */
		function setStyle($property, $value);
	}

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
		 * @param  string $rowField
		 * @return self
		 */
		function setRowField($rowField);


		/**
		 * @return string
		 */
		function getRowField();


		/**
		 * @param  array<string, mixed>|object $row
		 * @return string
		 */
		function formatValue($row);


		/**
		 * @param  callable|NULL $customRender
		 * @return self
		 */
		function setCustomRender($customRender);


		/**
		 * @return self
		 */
		function setValueProvider(callable $customRender = NULL);


		/**
		 * @return string|NULL
		 */
		function getSort();


		/**
		 * @param  string $sorting
		 * @return self
		 */
		function setSort($sorting);


		/**
		 * @param  bool|string|string[] $sortable
		 * @return self
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
		 * @return array<string, mixed>|NULL
		 */
		function getAttributes();


		/**
		 * @param  string $attr
		 * @param  scalar|NULL $value
		 * @return self
		 */
		function setAttribute($attr, $value);

		/**
		 * @param  string $property
		 * @param  scalar|NULL $value
		 * @return self
		 */
		function setStyle($property, $value);
	}

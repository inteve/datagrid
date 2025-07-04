<?php

	declare(strict_types=1);

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
		 * @return $this
		 */
		function setRowField($rowField);


		/**
		 * @return string
		 */
		function getRowField();


		/**
		 * @param  array<string, mixed>|object $row
		 * @return string|\Nette\Utils\Html
		 */
		function formatValue($row);


		/**
		 * @param  callable|NULL $customRender
		 * @return $this
		 */
		function setCustomRender($customRender);


		/**
		 * @return $this
		 */
		function setValueProvider(?callable $customRender = NULL);


		/**
		 * @return string|NULL
		 */
		function getSort();


		/**
		 * @param  string $sorting
		 * @return $this
		 */
		function setSort($sorting);


		/**
		 * @param  bool|string|string[] $sortable
		 * @return $this
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
		 * @return $this
		 */
		function setAttribute($attr, $value);

		/**
		 * @param  string $property
		 * @param  scalar|NULL $value
		 * @return $this
		 */
		function setStyle($property, $value);
	}

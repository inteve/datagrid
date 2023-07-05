<?php

	namespace Inteve\DataGrid;

	use Nette\Forms\Controls\BaseControl;


	interface IFilter
	{
		const EQUAL = 0;
		const LIKE = 1;


		/**
		 * @return string
		 */
		function getName();


		/**
		 * @return string
		 */
		function getLabel();


		/**
		 * @return bool
		 */
		function isActive();


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
		 * @param  string $value
		 * @return self
		 */
		function setValue($value);


		/**
		 * @return string|NULL
		 */
		function getValue();


		/**
		 * @param  callable|int $condition
		 * @return self
		 */
		function setCondition($condition);


		/**
		 * @param  mixed $value
		 * @return self
		 */
		function setFormValue($value);


		/**
		 * @return BaseControl
		 */
		function getFormControl();


		/**
		 * @return FilterCondition|FilterCondition[]
		 */
		function prepareCondition();
	}

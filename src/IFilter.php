<?php

	namespace Inteve\DataGrid;

	use Nette\Forms\IControl;


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
		 * @param  string
		 */
		function setRowField($rowField);


		/**
		 * @param  string
		 */
		function getRowField();


		/**
		 * @param  string
		 */
		function setValue($value);


		/**
		 * @return string|NULL
		 */
		function getValue();


		/**
		 * @param  callback|int
		 * @return self
		 */
		function setCondition($condition);


		/**
		 * @param  mixed
		 */
		function setFormValue($value);


		/**
		 * @return IControl
		 */
		function getFormControl();


		/**
		 * @return FilterCondition|FilterCondition[]
		 */
		function prepareCondition();
	}

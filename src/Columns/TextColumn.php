<?php

	namespace Inteve\DataGrid\Columns;


	class TextColumn extends AbstractColumn
	{
		/**
		 * @param  mixed
		 * @param  object|array
		 * @return string
		 */
		protected function processDefaultFormat($value, $row)
		{
			return (string) $value;
		}
	}

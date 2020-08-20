<?php

	namespace Inteve\DataGrid;


	class LinkHelper
	{
		public function __construct()
		{
			throw new StaticClassException('This is static class.');
		}


		/**
		 * @param  mixed
		 * @return void
		 * @throws InvalidArgumentException
		 */
		public static function checkFactory($linkFactory)
		{
			if ($linkFactory instanceof \Nette\Application\UI\Link) {
				return;
			}

			if (!is_callable($linkFactory)) {
				throw new InvalidArgumentException('LinkFactory must be callable.');
			}
		}


		/**
		 * @param  mixed
		 * @param  string
		 * @param  scalar
		 * @return string
		 */
		public static function createUrl($linkFactory, $parameter, $rowId, $row)
		{
			if ($linkFactory instanceof \Nette\Application\UI\Link) {
				$linkFactory->setParameter($parameter, $rowId);
				return (string) $linkFactory;
			}

			return call_user_func($linkFactory, $row, $parameter, $rowId);
		}
	}

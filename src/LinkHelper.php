<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid;


	class LinkHelper
	{
		public function __construct()
		{
			throw new StaticClassException('This is static class.');
		}


		/**
		 * @param  mixed $linkFactory
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
		 * @param  callable(
		 *   array<string, mixed>|object $row,
		 *   string $parameter,
		 *   scalar $rowId,
		 * ):(string|\Nette\Application\UI\Link)|\Nette\Application\UI\Link $linkFactory
		 * @param  string $parameter
		 * @param  scalar $rowId
		 * @param  array<string, mixed>|object $row
		 * @return string
		 */
		public static function createUrl($linkFactory, $parameter, $rowId, $row)
		{
			if ($linkFactory instanceof \Nette\Application\UI\Link) {
				$linkFactory->setParameter($parameter, $rowId);
				return (string) $linkFactory;
			}

			return (string) call_user_func($linkFactory, $row, $parameter, $rowId);
		}
	}

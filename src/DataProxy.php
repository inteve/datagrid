<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class DataProxy
	{
		use \Nette\SmartObject;

		/** @var IDataSource */
		private $dataSource;

		/** @var IColumn[] */
		private $columns;

		/** @var IFilter[] */
		private $filters;

		/** @var IColumn[] */
		private $sorts;

		/** @var int */
		private $page;

		/** @var string */
		private $itemsOnPage;

		/** @var DataSourceResult */
		private $result;


		public function __construct(
			IDataSource $dataSource,
			array $columns,
			array $filters,
			array $sorts,
			$page,
			$itemsOnPage
		)
		{
			$this->dataSource = $dataSource;
			$this->columns = $columns;
			$this->filters = $filters;
			$this->sorts = $sorts;
			$this->page = $page;
			$this->itemsOnPage = $itemsOnPage;
		}


		/**
		 * @return DataSourceResult
		 */
		public function getResult()
		{
			if (!$this->result) {
				$this->result = $this->dataSource->getData(
					$this->columns,
					$this->filters,
					$this->sorts,
					$this->page,
					$this->itemsOnPage
				);
			}

			return $this->result;
		}


		/**
		 * @return array
		 */
		public function getRows()
		{
			return $this->getResult()->getRows();
		}


		/**
		 * @param  int[]|string[]|NULL
		 * @param  int
		 * @return array
		 */
		public function getSelectedRows(array $selected = NULL, $emptySelection)
		{
			if ($selected === NULL) {
				if ($emptySelection === BulkAction::SELECT_NONE) {
					return array();

				} elseif ($emptySelection === BulkAction::SELECT_PAGE) {
					return $this->getRows();

				} elseif ($emptySelection === BulkAction::SELECT_ALL) {
					$result = $this->dataSource->getData($this->columns, $this->filters, NULL, NULL);
					return $result->getRows();

				} else {
					throw new \Inteve\DataGrid\InvalidArgumentException("Uknow empty selection type '$emptySelection'.");
				}

			} elseif (empty($selected)) {
				return array();
			}

			$res = array();
			$selected = array_flip($selected);
			$rows = $this->getRows();

			foreach ($rows as $row) {
				$rowId = $this->dataSource->getRowId($row);

				if (isset($selected[$rowId])) {
					$res[] = $row;
				}
			}

			return $res;
		}
	}

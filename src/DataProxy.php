<?php

	declare(strict_types=1);

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

		/** @var int|NULL */
		private $itemsOnPage;

		/** @var DataSourceResult */
		private $result;


		/**
		 * @param IColumn[] $columns
		 * @param IFilter[] $filters
		 * @param IColumn[] $sorts
		 * @param int $page
		 * @param int|NULL $itemsOnPage
		 */
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
					DataPaging::create($this->page, $this->itemsOnPage)
				);
			}

			return $this->result;
		}


		/**
		 * @return array<array<string, mixed>|object>
		 */
		public function getRows()
		{
			return $this->getResult()->getRows();
		}


		/**
		 * @param  array<int|string>|NULL $selected
		 * @param  int $emptySelection
		 * @return array<array<string, mixed>|object>
		 */
		public function getSelectedRows(?array $selected, $emptySelection)
		{
			if ($selected === NULL) {
				if ($emptySelection === BulkAction::SELECT_NONE) {
					return [];

				} elseif ($emptySelection === BulkAction::SELECT_PAGE) {
					return $this->getRows();

				} elseif ($emptySelection === BulkAction::SELECT_ALL) {
					$result = $this->dataSource->getData($this->columns, $this->filters, [], DataPaging::create(NULL, NULL));
					return $result->getRows();

				} else {
					throw new \Inteve\DataGrid\InvalidArgumentException("Uknow empty selection type '$emptySelection'.");
				}

			} elseif (empty($selected)) {
				return [];
			}

			$res = [];
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

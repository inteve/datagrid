<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;
	use Nette\Utils\Paginator;
	use Nette\Utils\Strings;


	class DataGrid extends \Nette\Application\UI\Control
	{
		const TEMPLATE_DEFAULT = 'default';

		/** @var array  @persistent */
		public $filter = [];

		/** @var string  @persistent */
		public $sort = '';

		/** @var int  @persistent */
		public $page = 1;

		/** @var int|NULL  @persistent */
		public $perPage = NULL;

		/** @var IDataSource */
		private $dataSource;

		/** @var int */
		private $itemsOnPage = 20;

		/** @var int[]|NULL */
		private $perPageSteps = NULL;

		/** @var array */
		private $columns = [];

		/** @var array */
		private $filters = [];

		/** @var array */
		private $sorts = [];

		/** @var array */
		private $actions = [];

		/** @var BulkAction */
		private $bulkActions = [];

		/** @var array|NULL */
		private $defaultSort = [];

		/** @var callback|NULL */
		private $rowAttributes;

		/** @var string */
		private $templateFile;

		/** @var array */
		private $templateParameters;


		public function __construct(IDataSource $dataSource)
		{
			$this->dataSource = $dataSource;
			$this->setTemplateFile(self::TEMPLATE_DEFAULT);
		}


		/**
		 * @param  string  file path or template name
		 * @return self
		 */
		public function setTemplateFile($file, array $parameters = [])
		{
			Assert::string($file);

			if ($file === self::TEMPLATE_DEFAULT) {
				$file = __DIR__ . '/templates/' . $file . '/@grid.latte';
			}

			$this->templateFile = $file;
			$this->templateParameters = $parameters;
			return $this;
		}


		/**
		 * @param  int|NULL
		 * @param  bool|int[]
		 * @return self
		 */
		public function setItemsOnPage($itemsOnPage = NULL, $changeable = FALSE)
		{
			Assert::intOrNull($itemsOnPage);
			$this->itemsOnPage = $itemsOnPage;

			if ($changeable === FALSE) {
				$this->perPageSteps = NULL;
				$this->perPage = NULL;

			} elseif ($changeable === TRUE) {
				$this->perPageSteps = [10, 20, 50, 100];

			} elseif (is_array($changeable)) {
				$this->perPageSteps = $changeable;
			}

			if ($this->perPageSteps !== NULL) {
				$this->perPageSteps[] = $this->itemsOnPage;
				$this->perPageSteps = array_unique($this->perPageSteps);
				sort($this->perPageSteps, SORT_NUMERIC);
			}

			return $this;
		}


		/**
		 * @param  array  [column => (string) ASC|DESC]
		 * @return self
		 */
		public function setDefaultSort(array $sorts)
		{
			$this->defaultSort = [];

			foreach ($sorts as $name => $sorting) {
				$column = $this->getColumn($name);
				Assert::assert($column->isSortable(), "Column '$name' is not sortable.");
				$sorting = strtoupper($sorting);

				if ($sorting !== IColumn::ASC && $sorting !== IColumn::DESC) {
					throw new \Inteve\DataGrid\InvalidArgumentException("Invalid sorting '$sorting' for column '$name'.");
				}

				$this->defaultSort[$name] = $sorting;
			}

			return $this;
		}


		/**
		 * @param  callback
		 * @return self
		 */
		public function setRowAttributes($callback)
		{
			$this->rowAttributes = $callback;
			return $this;
		}


		/**
		 * @param  string
		 * @param  string
		 * @param  string|NULL
		 * @return Columns\TextColumn
		 */
		public function addTextColumn($name, $label, $rowField = NULL)
		{
			return $this->addColumn(new Columns\TextColumn($name, $label, $rowField));
		}


		/**
		 * @param  string
		 * @param  string
		 * @param  string|NULL
		 * @param  string|NULL
		 * @return Columns\NumberColumn
		 */
		public function addNumberColumn($name, $label, $suffix = NULL, $rowField = NULL)
		{
			return $this->addColumn(new Columns\NumberColumn($name, $label, $rowField))
				->setSuffix($suffix);
		}


		/**
		 * @param  string
		 * @param  string
		 * @param  callable|NULL
		 * @param  string|NULL
		 * @return Columns\LinkColumn
		 */
		public function addLinkColumn($name, $label, callable $link = NULL, $rowField = NULL)
		{
			return $this->addColumn(new Columns\LinkColumn($name, $label, $link, $rowField));
		}


		/**
		 * @param  string
		 * @param  string
		 * @param  string|NULL
		 * @return Columns\DateTimeColumn
		 */
		public function addDateColumn($name, $label, $rowField = NULL)
		{
			return $this->addColumn(new Columns\DateTimeColumn($name, $label, 'j.n.Y', $rowField));
		}


		/**
		 * @param  string
		 * @param  string
		 * @param  string|NULL
		 * @param  string
		 * @return Columns\DateTimeColumn
		 */
		public function addDateTimeColumn($name, $label, $rowField = NULL, $format = 'j.n.Y / H:i:s')
		{
			return $this->addColumn(new Columns\DateTimeColumn($name, $label, $format, $rowField));
		}


		/**
		 * @param  string
		 * @param  string
		 * @return Filters\TextFilter
		 */
		public function addTextFilter($name, $label, $rowField = NULL)
		{
			return $this->addFilter(new Filters\TextFilter($name, $label, $rowField))
				->setCondition(IFilter::LIKE);
		}


		/**
		 * @param  string
		 * @param  string
		 * @return Filters\SelectFilter
		 */
		public function addSelectFilter($name, $label, array $items, $rowField = NULL)
		{
			return $this->addFilter(new Filters\SelectFilter($name, $label, $items, $rowField));
		}


		/**
		 * @param  string
		 * @param  string
		 * @return Filters\MultiSelectFilter
		 */
		public function addMultiSelectFilter($name, $label, array $items, $rowField = NULL)
		{
			return $this->addFilter(new Filters\MultiSelectFilter($name, $label, $items, $rowField));
		}


		/**
		 * @param  string
		 * @param  string
		 * @param  Action
		 */
		public function addAction($name, $label, $link)
		{
			if (isset($this->actions[$name])) {
				throw new DuplicateException("Action '$name' already exists.");
			}

			return $this->actions[$name] = new Action($name, $label, $link, $this->dataSource);
		}


		/**
		 * @param  string
		 * @param  string
		 * @param  callback
		 * @param  int
		 * @return self
		 */
		public function addBulkAction($name, $label, $callback, $emptySelection = BulkAction::SELECT_NONE)
		{
			if (isset($this->bulkActions[$name])) {
				throw new DuplicateException("Bulk action '$name' already exists.");
			}

			return $this->bulkActions[$name] = new BulkAction($name, $label, $callback, $emptySelection);
		}


		public function handleResetFilter()
		{
			$this->filter = [];
			$this->page = 1;
			$this->redirect('this');
		}


		public function createComponentFilters()
		{
			$form = new \Nette\Application\UI\Form;
			$filters = $form->addContainer('filters');

			foreach ($this->filters as $filter) {
				$control = $filter->getFormControl();
				$control->caption = $filter->getLabel();
				$filters[$filter->getName()] = $control;
			}

			$form->addSubmit('apply', 'Hledat');
			$form->onSuccess[] = [$this, 'processFilters'];
			return $form;
		}


		/**
		 * @internal
		 */
		public function processFilters($form)
		{
			$values = $form->getValues();
			$this->filter = [];
			$this->page = 1;

			foreach ($values->filters as $name => $value) {
				$filter = $this->getFilter($name);
				$filter->setFormValue($value);
				$this->filter[$name] = $filter->getValue();
			}

			$this->redirect('this');
		}


		public function createComponentViewOptions()
		{
			$form = new \Nette\Application\UI\Form;

			if ($this->perPageSteps !== NULL) {
				$form->addSelect('perPage', 'Položek na stránku')
					->setItems($this->perPageSteps, FALSE)
					->setDefaultValue($this->getItemsOnPage());
			}

			$form->addSubmit('show', 'Zobrazit');
			$form->onSuccess[] = [$this, 'processViewOptions'];
			return $form;
		}


		/**
		 * @internal
		 */
		public function processViewOptions($form)
		{
			$values = $form->getValues();
			$this->page = 1;

			if ($this->perPageSteps !== NULL && isset($values->perPage)) {
				$this->perPage = $values->perPage;
			}

			if ($this->perPage === $this->itemsOnPage) {
				$this->perPage = NULL;
			}

			$this->redirect('this');
		}


		public function createComponentBulkAction()
		{
			$form = new \Nette\Application\UI\Form;
			$form->onSuccess[] = [$this, 'processBulkAction'];
			return $form;
		}


		/**
		 * @internal
		 */
		public function processBulkAction($form)
		{
			$dataProxy = $this->createDataProxy();
			$actions = $form->getHttpData($form::DATA_LINE | $form::DATA_KEYS, 'action[]');
			$bulkAction = NULL;

			foreach ($actions as $name => $label) {
				if (!isset($this->bulkActions[$name])) {
					continue;
				}

				$bulkAction = $this->bulkActions[$name];
			}

			if ($bulkAction === NULL) {
				throw new InvalidArgumentException("Missing bulk action.");
			}

			$values = $form->getHttpData($form::DATA_LINE, 'selected[]');
			$emptySelection = $bulkAction->getEmptySelection();

			if (empty($values) && $emptySelection === BulkAction::SELECT_NONE) {
				$this->getPresenter()->flashMessage('Nebyly vybrány žádné řádky.');
				$this->redirect('this');
			}

			$rows = $dataProxy->getSelectedRows(!empty($values) ? $values : NULL, $emptySelection);
			$callback = $bulkAction->getCallback();
			call_user_func($callback, $rows);
			$this->redirect('this'); // fallback
		}


		/**
		 * @param  array
		 * @return void
		 */
		public function loadState(array $params)
		{
			parent::loadState($params);

			// sorts
			$this->sorts = [];

			if (!is_string($this->sort)) {
				$this->sort = '';
			}

			$sorts = $this->defaultSort;

			if ($this->sort !== '') {
				$sorts = $this->unserializeSort($this->sort);
			}

			foreach ($sorts as $column => $sort) {
				$this->sorts[] = $this->getColumn($column)->setSort(strtoupper($sort));
			}

			// items on page changes
			if ($this->perPageSteps === NULL) {
				$this->perPage = NULL;

			} elseif ($this->perPage !== NULL) {
				$this->perPage = (int) $this->perPage;

				if (!in_array($this->perPage, $this->perPageSteps, TRUE)) {
					$this->perPage = NULL;
				}

				if ($this->perPage === $this->itemsOnPage) {
					$this->perPage = NULL;
				}
			}

			// filters
			if (!is_array($this->filter)) {
				$this->filter = [];
			}

			$filters = $this->filter;

			foreach ($filters as $name => $value) {
				if (!is_string($value)) {
					continue;
				}

				$filter = $this->getFilter($name);
				$filter->setValue(Strings::trim($value));
				$this->filter[$name] = $filter->getValue();

				if ($this->filter[$name] === NULL) {
					unset($this->filter[$name]);
				}
			}
		}


		/**
		 * @return void
		 */
		public function render()
		{
			Assert::string($this->templateFile, 'Parameter $templateFile is not set, use $grid->setTemplateFile().');
			$result = $this->getDataResult();
			$template = $this->createTemplate();
			$template->setParameters($this->templateParameters);
			$template->grid = $this;
			$template->rows = $result->getRows();
			$template->paginator = $this->createPaginator($result, $this->page, $this->getItemsOnPage());
			$template->page = $this->page;
			$template->columns = $this->columns;
			$template->filters = $this->filters;
			$template->actions = $this->actions;
			$template->bulkActions = $this->bulkActions;
			$template->render($this->templateFile);
		}


		/**
		 * @param  array|object
		 * @return scalar
		 */
		public function getRowId($row)
		{
			return $this->dataSource->getRowId($row);
		}


		/**
		 * @return array|NULL
		 */
		public function getRowAttributes($row, array $defaultAttributes = NULL)
		{
			$attrs = NULL;

			if ($this->rowAttributes !== NULL) {
				$attrs = call_user_func($this->rowAttributes, $row);
			}

			return Helpers::mergeAttributes($attrs, $defaultAttributes);
		}


		/**
		 * @return string
		 */
		public function getSortUrl(IColumn $column)
		{
			$name = $column->getName();

			if (!$column->isSortable()) {
				throw new InvalidArgumentException("Column '$name' is not sortable.");
			}

			$sort = $column->getSort();
			$params = [
				'sort' => NULL,
			];
			$switch = NULL;

			if ($sort === NULL) {
				$switch = 'asc';

			} elseif ($sort === IColumn::ASC) {
				$switch = 'desc';
			}

			if ($switch !== NULL) {
				$params['sort'] = $this->serializeSort($name, $switch);
			}

			return $this->link('this', $params);
		}


		/**
		 * @return DataSourceResult
		 */
		private function getDataResult()
		{
			return $this->dataSource->getData($this->columns, $this->filters, $this->sorts, DataPaging::create($this->page, $this->getItemsOnPage()));
		}


		/**
		 * @return DataProxy
		 */
		private function createDataProxy()
		{
			return new DataProxy(
				$this->dataSource,
				$this->columns,
				$this->filters,
				$this->sorts,
				$this->page,
				$this->getItemsOnPage()
			);
		}


		/**
		 * @param  DataSourceResult
		 * @param  int
		 * @param  int|NULL $itemsOnPage
		 * @return Paginator|NULL
		 */
		private function createPaginator(DataSourceResult $result, $page, $itemsOnPage)
		{
			if ($itemsOnPage === NULL) {
				return NULL;
			}

			$paginator = new Paginator;
			$paginator->setPage($page);
			$paginator->setItemCount($result->getCount());
			$paginator->setItemsPerPage($itemsOnPage);
			return $paginator;
		}


		/**
		 * @param  string
		 * @return bool
		 */
		private function hasColumn($name)
		{
			return isset($this->columns[$name]);
		}


		/**
		 * @param  string
		 * @return bool
		 */
		private function getColumn($name)
		{
			if (!$this->hasColumn($name)) {
				throw new MissingException("Column '$name' not exists.");
			}
			return $this->columns[$name];
		}


		/**
		 * @param  string
		 * @return bool
		 */
		private function hasFilter($name)
		{
			return isset($this->filters[$name]);
		}


		/**
		 * @param  string
		 * @return bool
		 */
		private function getFilter($name)
		{
			if (!$this->hasFilter($name)) {
				throw new MissingException("Filter '$name' not exists.");
			}
			return $this->filters[$name];
		}


		/**
		 * @return IColumn
		 */
		private function addColumn(IColumn $column)
		{
			$name = $column->getName();

			if ($this->hasColumn($name)) {
				throw new DuplicateException("Column '$name' already exists.");
			}

			return $this->columns[$name] = $column;
		}


		/**
		 * @return IFilter
		 */
		private function addFilter(IFilter $filter)
		{
			$name = $filter->getName();

			if ($this->hasFilter($name)) {
				throw new DuplicateException("Filter '$name' already exists.");
			}

			return $this->filters[$name] = $filter;
		}


		/**
		 * @param  string
		 * @param  string
		 * @return string
		 */
		private function serializeSort($column, $sort)
		{
			return $column . ':' . strtolower($sort);
		}


		/**
		 * @param  string
		 * @return array
		 */
		private function unserializeSort($s)
		{
			$parts = explode(':', $s, 2);

			if (!isset($parts[1])) {
				return [];
			}

			return [$parts[0] => $parts[1]];
		}


		/**
		 * @return int|NULL
		 */
		private function getItemsOnPage()
		{
			return $this->perPage !== NULL ? $this->perPage : $this->itemsOnPage;
		}
	}

<?php

	declare(strict_types=1);

	namespace Inteve\DataGrid\DataSources;

	use CzProject\Assert\Assert;
	use Inteve\DataGrid\DataPaging;
	use Inteve\DataGrid\DataSourceResult;
	use Inteve\DataGrid\FilterCondition;
	use Inteve\DataGrid\IColumn;
	use Inteve\DataGrid\IDataSource;
	use Inteve\DataGrid\IFilter;


	/**
	 * @template T of \LeanMapper\Entity
	 */
	class LeanMapperQuery implements IDataSource
	{
		use \Nette\SmartObject;

		const GROUP_BY_AUTO = TRUE;
		const GROUP_BY_NONE = FALSE;

		/** @var \LeanMapperQuery\Query<T> */
		private $query;

		/** @var \LeanMapper\IMapper */
		private $mapper;


		/**
		 * @param \LeanMapperQuery\Query<T> $query
		 */
		public function __construct(\LeanMapperQuery\Query $query, \LeanMapper\IMapper $mapper)
		{
			$this->query = $query;
			$this->mapper = $mapper;
		}


		public function getRowId($row)
		{
			if (!($row instanceof \LeanMapper\Entity)) {
				throw new \Inteve\DataGrid\InvalidArgumentException('Row must be instance of LeanMapper\Entity, ' . gettype($row) . ' given.');
			}

			$table = $this->mapper->getTable(get_class($row));
			$primaryKey = $this->mapper->getEntityField($table, $this->mapper->getPrimaryKey($table));
			$rowId = $row->{$primaryKey};

			if (!is_scalar($rowId)) {
				throw new \Inteve\DataGrid\InvalidStateException("Row ID must be scalar, " . gettype($rowId) . " given.");
			}

			return $rowId;
		}


		public function getData(array $columns, array $filters, array $sorts, DataPaging $paging)
		{
			$query = $this->query;

			if (!method_exists($query, 'count')) {
				throw new \Inteve\DataGrid\InvalidStateException("Missing method " . \LeanMapperQuery\Query::class . '::count(): int');
			}

			if (!method_exists($query, 'find')) {
				throw new \Inteve\DataGrid\InvalidStateException("Missing method " . \LeanMapperQuery\Query::class . '::find(): array');
			}

			// filtering
			foreach ($filters as $filter) {
				if (!$filter->isActive()) {
					continue;
				}

				$this->applyCondition($query, $filter->prepareCondition());
			}

			$count = $query->count();

			if (!is_int($count)) {
				throw new \Inteve\DataGrid\InvalidStateException("Count must be int, " . gettype($count) . 'given.');
			}

			// sorting
			foreach ($sorts as $column) {
				if (!$column->isSortable()) {
					continue;
				}

				$sort = $column->getSort();

				if ($sort === NULL) {
					continue;
				}

				$fields = $column->getSortableFields();

				if (is_array($fields)) {
					foreach ($fields as $field) {
						$query->orderBy('@' . $field . ' ' . $sort);
					}
				}
			}

			// pager
			if ($paging->hasOffset()) {
				$query->offset($paging->getOffset());
			}

			if ($paging->hasLimit()) {
				$query->limit($paging->getLimit());
			}

			$rows = $query->find();

			if (!is_array($rows)) {
				throw new \Inteve\DataGrid\InvalidStateException("Rows must be array, " . gettype($rows) . 'given.');
			}

			return new DataSourceResult($rows, $count);
		}


		/**
		 * @param  \LeanMapperQuery\Query<T> $query
		 * @param  FilterCondition|FilterCondition[] $conditions
		 * @return void
		 */
		protected function applyCondition(\LeanMapperQuery\Query $query, $conditions)
		{
			if (!is_array($conditions)) {
				$conditions = [$conditions];
			}

			$statements = [];
			$values = [];

			foreach ($conditions as $condition) {
				$statement = '@' . $condition->getField();
				$value = $condition->getValue();

				foreach ($condition->getModifiers() as $modifier) {
					$statement = strtoupper($modifier->getName()) . '(' . $statement;

					foreach ($modifier->getArguments() as $modifierArg) {
						$statement .= ', ?';
						$values[] = $modifierArg;
					}

					$statement .= ')';
				}

				$statement .= ' ';
				$comparison = $condition->getComparison();

				if ($comparison === IFilter::EQUAL) {
					if (is_null($value)) {
						$statement .= 'IS NULL';

					} elseif (is_array($value)) {
						$statement .= 'IN %in';
						$values[] = $value;

					} elseif (is_string($value)) {
						$statement .= '= %s';
						$values[] = $value;

					} elseif (is_int($value)) {
						$statement .= '= %i';
						$values[] = $value;

					} else {
						$statement .= '= ?';
						$values[] = $value;
					}

				} elseif ($comparison === IFilter::LIKE) {
					$statement .= 'LIKE %~like~';
					$values[] = $value;

				} else {
					throw new \Inteve\DataGrid\InvalidArgumentException("Unknow comparison '$comparison'.");
				}

				$statements[] = $statement;
			}

			array_unshift($values, implode(' OR ', $statements));
			call_user_func_array([$query, 'where'], $values);
		}
	}

<?php

	namespace Inteve\DataGrid;

	use CzProject\Assert\Assert;


	class DataPaging
	{
		/** @var int|NULL */
		private $offset;

		/** @var int|NULL */
		private $limit;


		/**
		 * @param  int|NULL $offset
		 * @param  int|NULL $limit
		 */
		public function __construct($offset, $limit)
		{
			Assert::intOrNull($offset);
			Assert::intOrNull($limit);

			if (is_int($offset)) {
				Assert::true($offset >= 0);
			}

			if (is_int($limit)) {
				Assert::true($limit >= 0);
			}

			$this->offset = $offset;
			$this->limit = $limit;
		}


		/**
		 * @return bool
		 */
		public function onlyOffset()
		{
			return $this->offset !== NULL && $this->limit === NULL;
		}


		/**
		 * @return bool
		 */
		public function onlyLimit()
		{
			return $this->offset === NULL && $this->limit !== NULL;
		}


		/**
		 * @return bool
		 */
		public function offsetAndLimit()
		{
			return $this->offset !== NULL && $this->limit !== NULL;
		}


		/**
		 * @return bool
		 */
		public function withoutPaging()
		{
			return $this->offset === NULL && $this->limit === NULL;
		}


		/**
		 * @return bool
		 */
		public function hasOffset()
		{
			return $this->offset !== NULL;
		}


		/**
		 * @return int
		 */
		public function getOffset()
		{
			if ($this->offset === NULL) {
				throw new InvalidStateException('Offset is not set.');
			}

			return $this->offset;
		}


		/**
		 * @return bool
		 */
		public function hasLimit()
		{
			return $this->limit !== NULL;
		}


		/**
		 * @return int
		 */
		public function getLimit()
		{
			if ($this->limit === NULL) {
				throw new InvalidStateException('Limit is not set.');
			}

			return $this->limit;
		}


		/**
		 * @param  int|NULL $page
		 * @param  int|NULL $itemsOnPage
		 * @return self
		 */
		public static function create($page, $itemsOnPage)
		{
			if ($page !== NULL && $itemsOnPage !== NULL) {
				return new self(($page - 1) * $itemsOnPage, $itemsOnPage);
			}

			if ($page !== NULL && $itemsOnPage === NULL) {
				return new self($page - 1, NULL);
			}

			return new self(NULL, $itemsOnPage);
		}
	}

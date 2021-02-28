<?php

use Inteve\DataGrid\DataPaging;
use Inteve\DataGrid\DataSources\ArrayDataSource;
use Inteve\DataGrid\DataSources\MergedDataSource;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$dataSourceA = new ArrayDataSource([
	['id' => 'A-1'],
	['id' => 'A-2'],
	['id' => 'A-3'],
	['id' => 'A-4'],
	['id' => 'A-5'],
]);

$dataSourceB = new ArrayDataSource([
	['id' => 'B-1'],
	['id' => 'B-2'],
	['id' => 'B-3'],
]);

$dataSourceC = new ArrayDataSource([
	['id' => 'C-1'],
	['id' => 'C-2'],
]);

$dataSource = new MergedDataSource([
	'A' => $dataSourceA,
	'B' => $dataSourceB,
	'C' => $dataSourceC,
]);


test(function () use ($dataSource) { // all
	$result = $dataSource->getData([], [], [], DataPaging::create(NULL, NULL));
	Assert::same([
		['id' => 'A-1'],
		['id' => 'A-2'],
		['id' => 'A-3'],
		['id' => 'A-4'],
		['id' => 'A-5'],
		['id' => 'B-1'],
		['id' => 'B-2'],
		['id' => 'B-3'],
		['id' => 'C-1'],
		['id' => 'C-2'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());
});


test(function () use ($dataSource) { // only limit
	$result = $dataSource->getData([], [], [], DataPaging::create(NULL, 3));
	Assert::same([
		['id' => 'A-1'],
		['id' => 'A-2'],
		['id' => 'A-3'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], DataPaging::create(NULL, 6));
	Assert::same([
		['id' => 'A-1'],
		['id' => 'A-2'],
		['id' => 'A-3'],
		['id' => 'A-4'],
		['id' => 'A-5'],
		['id' => 'B-1'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], DataPaging::create(NULL, 9));
	Assert::same([
		['id' => 'A-1'],
		['id' => 'A-2'],
		['id' => 'A-3'],
		['id' => 'A-4'],
		['id' => 'A-5'],
		['id' => 'B-1'],
		['id' => 'B-2'],
		['id' => 'B-3'],
		['id' => 'C-1'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], DataPaging::create(NULL, 20));
	Assert::same([
		['id' => 'A-1'],
		['id' => 'A-2'],
		['id' => 'A-3'],
		['id' => 'A-4'],
		['id' => 'A-5'],
		['id' => 'B-1'],
		['id' => 'B-2'],
		['id' => 'B-3'],
		['id' => 'C-1'],
		['id' => 'C-2'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());
});


test(function () use ($dataSource) { // only offset
	$result = $dataSource->getData([], [], [], new DataPaging(3, NULL));
	Assert::same([
		['id' => 'A-4'],
		['id' => 'A-5'],
		['id' => 'B-1'],
		['id' => 'B-2'],
		['id' => 'B-3'],
		['id' => 'C-1'],
		['id' => 'C-2'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], new DataPaging(5, NULL));
	Assert::same([
		['id' => 'B-1'],
		['id' => 'B-2'],
		['id' => 'B-3'],
		['id' => 'C-1'],
		['id' => 'C-2'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], new DataPaging(6, NULL));
	Assert::same([
		['id' => 'B-2'],
		['id' => 'B-3'],
		['id' => 'C-1'],
		['id' => 'C-2'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], new DataPaging(9, NULL));
	Assert::same([
		['id' => 'C-2'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());
});


test(function () use ($dataSource) { // offset + limit
	$result = $dataSource->getData([], [], [], DataPaging::create(1, 3));
	Assert::same([
		['id' => 'A-1'],
		['id' => 'A-2'],
		['id' => 'A-3'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], DataPaging::create(2, 3));
	Assert::same([
		['id' => 'A-4'],
		['id' => 'A-5'],
		['id' => 'B-1'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], DataPaging::create(3, 3));
	Assert::same([
		['id' => 'B-2'],
		['id' => 'B-3'],
		['id' => 'C-1'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], DataPaging::create(4, 3));
	Assert::same([
		['id' => 'C-2'],
	], $result->getRows());
	Assert::same(10, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], DataPaging::create(5, 3));
	Assert::same([], $result->getRows());
	Assert::same(10, $result->getTotalCount());
});

<?php

declare(strict_types=1);

use Inteve\DataGrid\DataPaging;
use Inteve\DataGrid\DataSources\ArrayDataSource;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

$dataSource = new ArrayDataSource([
	['id' => 1],
	['id' => 2],
	['id' => 3],
	['id' => 4],
	['id' => 5],
]);


test(function () use ($dataSource) { // all
	$result = $dataSource->getData([], [], [], DataPaging::create(NULL, NULL));
	Assert::same([
		['id' => 1],
		['id' => 2],
		['id' => 3],
		['id' => 4],
		['id' => 5],
	], $result->getRows());
	Assert::same(5, $result->getTotalCount());
});


test(function () use ($dataSource) { // only limit
	$result = $dataSource->getData([], [], [], DataPaging::create(NULL, 3));
	Assert::same([
		['id' => 1],
		['id' => 2],
		['id' => 3],
	], $result->getRows());
	Assert::same(5, $result->getTotalCount());
});


test(function () use ($dataSource) { // only offset
	$result = $dataSource->getData([], [], [], new DataPaging(3, NULL));
	Assert::same([
		['id' => 4],
		['id' => 5],
	], $result->getRows());
	Assert::same(5, $result->getTotalCount());
});


test(function () use ($dataSource) { // offset + limit
	$result = $dataSource->getData([], [], [], DataPaging::create(1, 3));
	Assert::same([
		['id' => 1],
		['id' => 2],
		['id' => 3],
	], $result->getRows());
	Assert::same(5, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], DataPaging::create(2, 3));
	Assert::same([
		['id' => 4],
		['id' => 5],
	], $result->getRows());
	Assert::same(5, $result->getTotalCount());

	$result = $dataSource->getData([], [], [], DataPaging::create(3, 3));
	Assert::same([], $result->getRows());
	Assert::same(5, $result->getTotalCount());
});

<?php

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
	$result = $dataSource->getData([], [], [], NULL, NULL);
	Assert::same([
		['id' => 1],
		['id' => 2],
		['id' => 3],
		['id' => 4],
		['id' => 5],
	], $result->getRows());
	Assert::same(5, $result->getCount());
});


test(function () use ($dataSource) { // only limit
	$result = $dataSource->getData([], [], [], NULL, 3);
	Assert::same([
		['id' => 1],
		['id' => 2],
		['id' => 3],
	], $result->getRows());
	Assert::same(5, $result->getCount());
});


test(function () use ($dataSource) { // offset + limit
	$result = $dataSource->getData([], [], [], 1, 3);
	Assert::same([
		['id' => 1],
		['id' => 2],
		['id' => 3],
	], $result->getRows());
	Assert::same(5, $result->getCount());

	$result = $dataSource->getData([], [], [], 2, 3);
	Assert::same([
		['id' => 4],
		['id' => 5],
	], $result->getRows());
	Assert::same(5, $result->getCount());

	$result = $dataSource->getData([], [], [], 3, 3);
	Assert::same([], $result->getRows());
	Assert::same(5, $result->getCount());
});

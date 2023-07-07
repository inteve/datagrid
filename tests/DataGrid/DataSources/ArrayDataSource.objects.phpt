<?php

declare(strict_types=1);

use Inteve\DataGrid\DataSources\ArrayDataSource;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

test(function () {
	$dataSource = new ArrayDataSource([]);

	Assert::same(2, $dataSource->getRowId((object) [
		'id' => 2,
	]));
});

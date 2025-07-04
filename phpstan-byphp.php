<?php

declare(strict_types=1);

if (PHP_VERSION_ID < 80100) {
	return [
		'includes' => [
			'phpstan-baseline.php-8.0.neon',
		],
	];

}

return [
	'includes' => [
		'phpstan-baseline.neon',
	],
];

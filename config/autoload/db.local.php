<?php
/**
 * Local configuration.
 *
 * Copy this file to `local.php` and change its settings as required.
 * `local.php` is ignored by git and safe to use for local and sensitive data like usernames and passwords.
 */

declare(strict_types=1);

return [
	'db' => [
		'adapters' => [
			'db1' => [
				'driver' => 'Mysqli',
				'database' => 'ze3',
				'username' => 'root',
				'password' => 'password',
			],
			'db2' => [
					'driver' => 'Mysqli',
					'database' => 'ze31',
					'username' => 'root',
					'password' => 'password',
			],
		]
	]
];
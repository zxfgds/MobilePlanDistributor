<?php

	return [
		'listen'           => 'http://0.0.0.0:8787',
		'transport'        => 'tcp',
		'context'          => [],
		'name'             => 'haoka',
		'count'            => cpu_count() * 4,
		'user'             => '',
		'group'            => '',
		'reusePort'        => FALSE,
		'event_loop'       => '',
		'stop_timeout'     => 2,
		'pid_file'         => runtime_path() . '/haoka.pid',
		'status_file'      => runtime_path() . '/haoka.status',
		'stdout_file'      => runtime_path() . '/logs/stdout.log',
		'log_file'         => runtime_path() . '/logs/haoka.log',
		'max_package_size' => 100 * 1024 * 1024,
	];

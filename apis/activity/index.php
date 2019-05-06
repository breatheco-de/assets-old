<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');

	$api = new \SlimAPI\SlimAPI([
		'name' => 'BreatheCode User Activity - BreatheCode Platform',
		'debug' => true,
        'jwt_key' => JWT_KEY,
        'jwt_clients' => JWT_CLIENTS
	]);

	$api->addReadme('/','./README.md');
	$api->addRoutes(require('routes.php'));
	$api->run();
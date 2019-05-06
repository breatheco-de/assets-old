<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');

	$api = new \SlimAPI\SlimAPI([
		'name' => 'Replit API - BreatheCode Platform',
		'debug' => API_DEBUG,
        'jwt_key' => JWT_KEY,
        'jwt_clients' => JWT_CLIENTS
	]);

	$api->addDB('json', new \JsonPDO\JsonPDO('data/','[]',false));
	$api->addReadme('/','./README.md');
	$api->addRoutes(require('routes.php'));
	$api->run();
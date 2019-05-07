<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require_once('./StreamingFunctions.php');

	$api = new \SlimAPI\SlimAPI([
		'name' => 'Streaming API - BreatheCode Platform',
		'debug' => true,
        'jwt_key' => JWT_KEY,
        'jwt_clients' => JWT_CLIENTS
	]);

	$api->addDB('json', new \JsonPDO\JsonPDO('cohorts/','[]',false));
	$api->addReadme('/','./README.md');
	$api->addRoutes(require('routes.php'));
	$api->run();
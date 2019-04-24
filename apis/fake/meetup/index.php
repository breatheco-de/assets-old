<?php
	require_once('../../../vendor/autoload.php');
	require_once('../../../globals.php');
	
	$api = new \SlimAPI\SlimAPI([
		'debug' => API_DEBUG,
		'name' => 'Fake Meetups API',
		'allowedURLs' => 'all'
	]);
	$api->addReadme('/','./README.md');
	$api->addDB('json', new \JsonPDO\JsonPDO('data/','[]',false));
	$api->addRoutes(require('routes.php'));
	$api->run(); 
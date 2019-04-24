<?php
	require_once('../../vendor/autoload.php');
	
	$api = new \SlimAPI\SlimAPI([
		'name' => 'Replit API - BreatheCode Platform',
		'debug' => true
	]);
	
	$api->addDB('json', new \JsonPDO\JsonPDO('data/','[]',false));
	$api->addReadme('/','./README.md');
	$api->addRoutes(require('routes.php'));
	$api->run(); 
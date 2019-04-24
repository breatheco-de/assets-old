<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require('StaticAssetsFunctions.php');
	
	$api = new \SlimAPI\SlimAPI([
		'name' => 'Static Assets API - BreatheCode Platform',
		'debug' => true
	]);
	
	$api->addReadme('/','./README.md');
	$api->addDB('json', new \JsonPDO\JsonPDO('data/','[]',false));
	$api->addRoutes(require('routes.php'));
	$api->run(); 
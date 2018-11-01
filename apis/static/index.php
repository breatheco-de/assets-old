<?php
	require_once('../../vendor/autoload.php');
	require_once('../JsonPDO.php');
	require_once('../../globals.php');
	require_once('../SlimAPI.php');
	require_once('routes.php');
	require('StaticAssetsFunctions.php');
	
	$api = new SlimAPI([
		'name' => 'Static Assets API - BreatheCode Platform',
		'debug' => true
	]);
	
	$api->addReadme('/','./README.md');
	$api->addDB('json', new JsonPDO('data/','[]',false));
	$api = addAPIRoutes($api);
	$api->run(); 
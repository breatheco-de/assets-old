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
	$api->addDB('json', new JsonPDO('data/','[]',false));
	StaticAssetsFunctions::setAPI($api);
	$logs = StaticAssetsFunctions::deleteUnusedImages();
	print_r($logs);
	
	exit(0);
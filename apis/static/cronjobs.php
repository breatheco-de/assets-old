<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require_once('routes.php');
	require('StaticAssetsFunctions.php');
	
	$api = new  \SlimAPI\SlimAPI([
		'name' => 'Static Assets API - BreatheCode Platform',
		'debug' => true
	]);
	$api->addDB('json', new \JsonPDO\JsonPDO('data/','[]',false));
	StaticAssetsFunctions::setAPI($api);
	
	//fake deletion of images
	// $logs = StaticAssetsFunctions::deleteUnusedImages($fake=true); //true=fake
	$logs = StaticAssetsFunctions::deletePendingImages(); 
	$logs = StaticAssetsFunctions::deletePendingUploads(); 
	
	// real deletion of images
	//$logs = StaticAssetsFunctions::deleteUnusedImages(false); //false=hard delete
	//$logs = StaticAssetsFunctions::deleteUnusedImages(false); //false=hard delete
	
	print_r($logs);
	
	exit(0);
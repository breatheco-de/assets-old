<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	
	$api = new \SlimAPI\SlimAPI([
		'name' => 'BreatheCode Student Github Integration - BreatheCode Platform',
		'debug' => true
	]);
	
	$api->addReadme('/','./README.md');
	$api->addRoutes(require('routes.php'));
	$api->run(); 
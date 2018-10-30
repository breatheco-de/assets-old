<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require_once('../SlimAPI.php');
	require_once('routes.php');
	
	$api = new SlimAPI([
		'name' => 'BreatheCode Messaging - BreatheCode Platform',
		'debug' => true
	]);
	
	$api->addReadme('/','./README.md');
	$api = addAPIRoutes($api);
	$api->run(); 
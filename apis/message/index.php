<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	
	$api = new \SlimAPI\SlimAPI([
		'name' => 'BreatheCode Messaging - BreatheCode Platform',
		'jwt_key' => JWT_KEY,
		'debug' => API_DEBUG
	]);
	
	$api->addReadme('/','./README.md');
	$api->addRoutes(require('routes.php'));
	$api->run(); 
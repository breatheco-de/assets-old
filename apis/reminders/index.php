<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	
	$api = new \SlimAPI\SlimAPI([
		'name' => 'BreatheCode Reminders - BreatheCode Platform',
		'debug' => API_DEBUG
	]);
	
	$api->addReadme('/','./README.md');
	$api->addRoutes(require('routes.php'));
	$api->run(); 
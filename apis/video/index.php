<?php
	require_once('../../vendor/autoload.php');
	require_once('../SlimAPI.php');
	require_once('routes.php');
	
	$api = new SlimAPI([
		'debug' => API_DEBUG,
		'name' => 'Video API'
	]);
	$api->addReadme('/','./README.md');
	$api = addAPIRoutes($api);
	$api->run(); 
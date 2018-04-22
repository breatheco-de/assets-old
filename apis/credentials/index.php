<?php
	require_once('../../vendor/autoload.php');
	require_once('../SlimAPI.php');
	require('routes.php');
	
	$api = new SlimAPI([
		'name' => 'Credentials',
	]);
	$api->addReadme('/','./README.md');
	$api = addAPIRoutes($api);
	$api->run(); 
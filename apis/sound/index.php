<?php
	require_once('../../vendor/autoload.php');
	require_once('../SlimAPI.php');
	require_once('../JsonPDO.php');
	require('routes.php');
	$api = new SlimAPI([
		'debug' => API_DEBUG,
		'name' => 'Sounds',
	]);
	$api->addReadme('/','./README.md');
	$api = addAPIRoutes($api);
	$api->run(); 
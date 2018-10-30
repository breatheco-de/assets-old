<?php
	require_once('../../../vendor/autoload.php');
	require_once('../../../globals.php');
	require_once('../../JsonPDO.php');
	require_once('../../SlimAPI.php');
	require('routes.php');
	
	$api = new SlimAPI([
		'debug' => API_DEBUG,
		'name' => 'Fake Meetups API',
		'allowedURLs' => 'all'
	]);
	$api->addReadme('/','./README.md');
	$api->addDB('json', new JsonPDO('data/','[]',false));
	$api = addAPIRoutes($api);
	$api->run(); 
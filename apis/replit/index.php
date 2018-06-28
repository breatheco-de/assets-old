<?php
	require_once('../../vendor/autoload.php');
	require_once('../JsonPDO.php');
	require_once('../../globals.php');
	require_once('../SlimAPI.php');
	require_once('routes.php');
	require('ReplitFunctions.php');
	
	$api = new SlimAPI([
		'name' => 'Replit API - BreatheCode Platform',
		'debug' => true
	]);
	
	$api->addDB('json', new JsonPDO('cohorts/','[]',false));
	$api->addReadme('/','./README.md');
	$api = addAPIRoutes($api);
	$api->run(); 
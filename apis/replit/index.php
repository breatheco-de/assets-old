<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require('ReplitFunctions.php');
	
	$api = new \SlimAPI\SlimAPI([
		'name' => 'Replit API - BreatheCode Platform',
		'debug' => true
	]);
	
	$api->addDB('json', new \JsonPDO\JsonPDO('cohorts/','[]',false));
	$api->addReadme('/','./README.md');
	$api->addRoutes(require('routes.php'));
	$api->run(); 
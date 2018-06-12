<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require_once('../JsonPDO.php');
	require_once('../SlimAPI.php');
	require('routes.php');
	require('EventFunctions.php');
	
	$api = new SlimAPI([
		'debug' => API_DEBUG,
		'name' => 'Events API'
	]);
	$api->addReadme('/','./README.md');
	$pdo = new \PDO( 'sqlite:db.sqlite3' );
	$db = new \LessQL\Database( $pdo );
	$db->setPrimary( 'event', 'id' );
	$api->addDB('sqlite', $db);
	$api = addAPIRoutes($api);
	$api->run(); 
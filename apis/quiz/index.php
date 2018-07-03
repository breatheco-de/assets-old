<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require_once('../SlimAPI.php');
	require_once('../JsonPDO.php');
	require_once('routes.php');
	
	$api = new SlimAPI([
		'name' => 'Quiz API'
	]);
	$api->addReadme('/','./README.md');
	$api->addDB('json', new JsonPDO('data/','[]',false));
	
	$pdo = new \PDO( 'sqlite:db.sqlite3' );
	$db = new \LessQL\Database( $pdo );
	$db->setPrimary( 'response', ['quiz_slug','user_id'] );
	$api->addDB('sqlite', $db);
	$api = addAPIRoutes($api);
	$api->run(); 
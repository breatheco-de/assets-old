<?php
	require_once('../../../vendor/autoload.php');
	require_once('../../../globals.php');
	
	$api = new \SlimAPI\SlimAPI([
		'debug' => API_DEBUG,
		'name' => 'Fake Contacts API',
		'allowedURLs' => 'all'
	]);
	$api->addReadme('/','./README.md');
	$pdo = new \PDO( 'sqlite:db.sqlite3' );
	$db = new \LessQL\Database( $pdo );
	$db->setPrimary( 'fake_contact_list', 'id' );
	$api->addDB('sqlite', $db);
	$api->addRoutes(require('routes.php'));
	$api->run(); 
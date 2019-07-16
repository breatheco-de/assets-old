<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');

	$api = new \SlimAPI\SlimAPI([
        'name' => 'NPS Feedback System - BreatheCode Platform',
		'debug' => API_DEBUG
	]);

	$pdo = new \PDO( 'sqlite:db.sqlite3' );
	$db = new \LessQL\Database( $pdo );
	$db->setPrimary( 'response', ['user_id'] );
	$api->addDB('sqlite', $db);
	$api->addRoutes(require('routes.php'));
	$api->run();
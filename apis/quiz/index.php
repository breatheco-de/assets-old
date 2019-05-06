<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');

	$api = new \SlimAPI\SlimAPI([
		'name' => 'Quiz API',
        'jwt_key' => JWT_KEY,
        'jwt_clients' => JWT_CLIENTS
	]);
	$api->addReadme('/','./README.md');
	$api->addDB('json', new \JsonPDO\JsonPDO('data/','[]',false));

	$pdo = new \PDO( 'sqlite:db.sqlite3' );
	$db = new \LessQL\Database( $pdo );
	$db->setPrimary( 'response', ['quiz_slug','user_id'] );
	$api->addDB('sqlite', $db);
	$api->addRoutes(require('routes.php'));
	$api->run();
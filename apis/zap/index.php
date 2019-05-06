<?php
    require('../../vendor/autoload.php');
    require_once('../../globals.php');
    require_once('../SlimAPI.php');
    require_once('../JsonPDO.php');
    require_once('routes.php');

	$api = new SlimAPI([
	    'debug' => API_DEBUG,
	    'name' => 'Zaps API',
        'jwt_key' => JWT_KEY,
        'jwt_clients' => JWT_CLIENTS
	]);
	$api->addReadme('/','./README.md');
    $api = addAPIRoutes($api);
	$api->run();
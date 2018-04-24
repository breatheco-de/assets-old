<?php
    require('../../vendor/autoload.php');
    require_once('../SlimAPI.php');
    require_once('routes.php');
    
	$api = new SlimAPI();
	$api->addReadme('/','./README.md');
    $api = addAPIRoutes($api);
	$api->run();
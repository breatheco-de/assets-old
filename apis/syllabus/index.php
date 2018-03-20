<?php
    require_once('../APIGenerator.php');
    
	$api = new APIGenerator('data/','[]',false);
	$api->get('full-stack', 'Get all calendar events', function($request, $data) use ($api){
        return $quizObj = $api->getJsonByName('full-stack-development');
	});
	$api->get('web-dev', 'Get all calendar events', function($request, $data) use ($api){
        return $quizObj = $api->getJsonByName('web-development');
	});
	$api->get('blockchain', 'Get all calendar events', function($request, $data) use ($api){
        return $quizObj = $api->getJsonByName('blockchain');
	});
	
	$api->run();
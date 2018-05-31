<?php
    require_once('../APIGenerator.php');
    
	$api = new APIGenerator('data.json','[]',false);
	$api->get('meetups', 'Get all calendar events', function($request, $data){
        return $data;
	});
	
	$api->run();
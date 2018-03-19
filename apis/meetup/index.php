<?php
    require_once('../APIGenerator.php');
    
	$api = new APIGenerator('data.json','[]',false);
	$api->get('meetups', 'Get all survays', function($request, $data){
        return $data;
	});
	
	$api->run();
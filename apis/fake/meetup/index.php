<?php
    require_once('../../APIGenerator.php');
    
	$api_events = new APIGenerator('events.json','[]',false);
	$api_events->get('events', 'Get all Calendar Events', function($request, $data){
        return $data;
	});
	
	$api_events->run();

	$api_meetups = new APIGenerator('meetups.json','[]',false);
	$api_meetups->get('meetups', 'Get all Meetups', function($request, $data){
        return $data;
	});
	
	$api_meetups->run();

	$api_session = new APIGenerator('session.json','[]',false);
	$api_session->get('session', 'Get session', function($request, $data){
        return $data;
	});
	
	$api_session->run();

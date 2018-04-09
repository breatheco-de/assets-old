<?php
	require_once('../../vendor/autoload.php');
	require_once('../JsonPDO.php');
	require_once('../SlimAPI.php');
	
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new SlimAPI();
	
	$api->addDB('json', new JsonPDO('data/','[]',false));
    
	$api->get('/full-stack', function (Request $request, Response $response, array $args) use ($api) {
        
        $syllabus = $api->db['json']->getJsonByName('full-stack-development');
        
		$teacher = $request->getQueryParam('teacher',null);
        if(isset($teacher)) return $response->withJson($syllabus);
        
        foreach ($syllabus['weeks'] as $week) {
        	$week->days = array_map(function($day){
        		if(isset($day->description)) return $day;
        		else return null;
        	}, $week->days);
        }
        return $response->withJson($syllabus);
	});
    
	$api->get('/web-dev', function (Request $request, Response $response, array $args) use ($api) {
        $syllabus = $api->db['json']->getJsonByName('web-development');
		
		$teacher = $request->getQueryParam('teacher',null);
        if(isset($teacher)) return $response->withJson($syllabus);
        
        foreach ($syllabus['weeks'] as $week) {
        	$week->days = array_map(function($day){
        		if(isset($day->description)) return $day;
        		else return null;
        	}, $week->days);
        }
        return $response->withJson($syllabus);
	});
    
	$api->get('/web-intro', function (Request $request, Response $response, array $args) use ($api) {
        $syllabus = $api->db['json']->getJsonByName('web-introduction');
        
		$teacher = $request->getQueryParam('teacher',null);
        if(isset($teacher)) return $response->withJson($syllabus);
        
        foreach ($syllabus['weeks'] as $week) {
        	$week->days = array_map(function($day){
        		if(isset($day->description)) return $day;
        		else return null;
        	}, $week->days);
        }
        return $response->withJson($syllabus);
	});
    
	$api->get('/blockchain', function (Request $request, Response $response, array $args) use ($api) {
        $syllabus = $api->db['json']->getJsonByName('blockchain');
        
		$teacher = $request->getQueryParam('teacher',null);
        if(isset($teacher)) return $response->withJson($syllabus);
        
        foreach ($syllabus['weeks'] as $week) {
        	$week->days = array_map(function($day){
        		if(isset($day->description)) return $day;
        		else return null;
        	}, $week->days);
        }
        return $response->withJson($syllabus);
	});
	
	$api->run();
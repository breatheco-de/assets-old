<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require_once('../JsonPDO.php');
	require_once('../SlimAPI.php');
	
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new SlimAPI([
		'name' => 'Replit API - BreatheCode Platform',
		'debug' => API_DEBUG
	]);
	
	$api->addDB('json', new JsonPDO('data/','[]',false));
	$api->addReadme('/','./README.md');
	$getSlug = function($slug){
	    $allSlugs = [
	        "full-stack" => "full-stack",
	        "web-development" => "web-development",
	        "coding-introduction" => "coding-introduction",
	        "blockchain" => "blockchain",
	        "boats" => "boats"
	    ];
	    if($allSlugs[$slug]) return $allSlugs[$slug];
	    else throw new Exception('Syllabus not found');
	};
    
	$api->get('/{slug}', function (Request $request, Response $response, array $args) use ($api, $getSlug) {
        
        $syllabus = $api->db['json']->getJsonByName($getSlug($args['slug']));
        
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
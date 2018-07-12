<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
require('./util_functions.php');
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper;

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

function addAPIRoutes($api){
	
	$api->addTokenGenerationPath();
	//get all cohorts and its replits
	$api->get('/programs', function (Request $request, Response $response, array $args) use ($api) {
		$programs = $api->db['json']->getJsonByName('programs');
		$schools = $api->db['json']->getJsonByName('schools');
		$responseObj = [];
		foreach($programs as $program){
			$program = (array) $program;
			$program['schools'] = [];
			foreach($schools as $s){
				if($api->db['json']->jsonExists($program['slug'].'-'.$s->slug)){
					$program['schools'][] = $s->slug;
				}
			}
			$responseObj[] = $program;
		}
	    return $response->withJson($responseObj);
	});
	//get all cohorts and its replits
	$api->get('/schools', function (Request $request, Response $response, array $args) use ($api) {
		$schools = $api->db['json']->getJsonByName('schools');
	    return $response->withJson($schools);
	});
	//get all cohorts and its replits
	$api->get('/program/{program_slug}', function (Request $request, Response $response, array $args) use ($api) {
		$programs = $api->db['json']->getJsonByName('programs');
		$program["program"] = $programs[$args['program_slug']];		
	    return $response->withJson($programs[$args['program_slug']]);
	});
	//get all cohorts and its replits
	$api->get('/compare/{program_slug}', function (Request $request, Response $response, array $args) use ($api) {
		
		$requestedSchools = [];
		if(!empty($_GET['schools'])){
			$requestedSchools = explode(",",$_GET['schools']);
			if(count($requestedSchools)<3) throw new Exception('A minimum of 3 schools have to be compared');
		}
		else{
			$schools = $api->db['json']->getJsonByName('schools');
			$requestedSchools = [];
			foreach($schools as $s){
				if($api->db['json']->jsonExists($args['program_slug'].'-'.$s->slug)){
					$requestedSchools[] = $s->slug;
				}
			}
			
		}
		
		$responseData = [];
		foreach($requestedSchools as $rs){
			$program = (array) $api->db['json']->getJsonByName($args['program_slug'].'-'.$rs);
			$responseData[$rs] = $program;
		}
		
		
	    return $response->withJson($responseData);
	});
	//get all cohorts and its replits
	$api->get('/permute/{program_slug}', function (Request $request, Response $response, array $args) use ($api) {
		
		$requestedSchools = [];
		if(!empty($_GET['schools'])){
			$requestedSchools = explode(",",$_GET['schools']);
			if(count($requestedSchools)<3) throw new Exception('A minimum of 3 schools have to be compared');
		}
		else{
			$schools = $api->db['json']->getJsonByName('schools');
			$requestedSchools = [];
			foreach($schools as $s){
				if($api->db['json']->jsonExists($args['program_slug'].'-'.$s->slug)){
					$requestedSchools[] = $s->slug;
				}
			}
			
		}
		
		$schoolsPermute = Functions::permute($requestedSchools, 3);
	    return $response->withJson($schoolsPermute);
	});
	//get all cohorts and its replits
	$api->get('/school/{school_slug}', function (Request $request, Response $response, array $args) use ($api) {
		$schools = $api->db['json']->getJsonByName('schools');
	    return $response->withJson($schools[$args['school_slug']]);
	});
	//get all cohorts and its replits
	$api->get('/{program_slug}/{school_slug}', function (Request $request, Response $response, array $args) use ($api) {
		$program = (array) $api->db['json']->getJsonByName($args['program_slug'].'-'.$args['school_slug']);
		$program["school"] = $school;
		
		$schools = $api->db['json']->getJsonByName('schools');
		if(isset($schools[$args['school_slug']])) $program["school"] = $schools[$args['school_slug']];

		$programs = $api->db['json']->getJsonByName('programs');
		if(isset($programs[$args['program_slug']])) $program["program"] = $programs[$args['program_slug']];
		
	    return $response->withJson($program);
	});

	return $api;
}
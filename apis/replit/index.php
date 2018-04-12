<?php
	require_once('../../vendor/autoload.php');
	require_once('../JsonPDO.php');
	require_once('../SlimAPI.php');
	
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new SlimAPI([
		'name' => 'Replit API - BreatheCode Platform',
		'debug' => true
	]);
	
	$api->addDB('json', new JsonPDO('cohorts/','[]',false));
	$api->addReadme('/','./README.md');
	
	//get all cohorts and its replits
	$api->get('/cohort', function (Request $request, Response $response, array $args) use ($api) {
		$content = $api->db['json']->getAllContent();
		$cohorts = [];
		foreach($content as $file => $replits){
			$cohorts[basename($file,".json")] = $replits;
		}
	    return $response->withJson($cohorts);
	});
	
	//get all replits from a cohort
	$api->get('/cohort/{cohort_slug}', function (Request $request, Response $response, array $args) use ($api) {
		if(empty($args['cohort_slug'])) throw new Exception('Invalid param cohort_slug');
		try{
			$cohorts = $api->db['json']->getJsonByName($args['cohort_slug']);
		}
		catch(Exception $e){
			throw new Exception('The Cohort has no Replit\'s or does not exists', 404);
		}
	    return $response->withJson($cohorts);
	});
	
	//save replits of a cohort
	$api->post('/cohort/{cohort_slug}', function (Request $request, Response $response, array $args) use ($api) {
		if(empty($args['cohort_slug'])) throw new Exception('Invalid param cohort_slug');
		try{
			$cohorts = $api->db['json']->getJsonByName($args['cohort_slug']);
		}
		catch(Exception $e){
			throw new Exception('The Cohort has no Replit\'s or does not exists', 404);
		}
			
		$data = $request->getParsedBody();
		if(!is_array($data)) throw new Exception('The body must be an object with all the replits of the cohort');
		
		$api->db['json']->toFile($args['cohort_slug'])->save($data);
		
	    return $response->withJson($cohorts);
	});
	
	$api->run(); 
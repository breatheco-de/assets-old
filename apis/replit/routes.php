<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper;
use \JsonPDO\JsonPDO;

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

return function($api){
	
	$api->addTokenGenerationPath();
	//get all cohorts and its replits
	$api->get('/template/all', function (Request $request, Response $response, array $args) use ($api) {
		
		$templatesPDO = new JsonPDO('_templates/','{}',false);
		$baseTemplates = $templatesPDO->getAllContent();
		$templates = []; 
		foreach($baseTemplates as $key => $temp){
			$templates[] = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($key));
		} 
		
	    return $response->withJson($templates);
	});

	//get all cohorts and its replits
	$api->get('/template/{profile_url}', function (Request $request, Response $response, array $args) use ($api) {
		
		$templatesPDO = new JsonPDO('_templates/','{}',false);
		$replits = $templatesPDO->getJsonByName($args['profile_url']);
		if(!$replits) throw new Exception('Invalid profile: '.$args['profile_url'], 400);
		
	    return $response->withJson($replits);
	});


	//get all cohorts and its replits
	$api->get('/templates', function (Request $request, Response $response, array $args) use ($api) {
		
		$templates = ReplitFunctions::getTemplates();
	    return $response->withJson($templates);
	});
	
	//get all cohorts and its replits
	$api->get('/cohort', function (Request $request, Response $response, array $args) use ($api) {
		
		$baseReplits = ReplitFunctions::getTemplates();
		
		$content = $api->db['json']->getAllContent();
		$cohorts = [];
		foreach($content as $file => $replits){
			foreach($baseReplits as $brepl){
				if(!empty($brepl->base)){
					if(empty($replits[$brepl->slug])) $replits[$brepl->slug] = $brepl->base;
				}
			} 
			$cohorts[basename($file,".json")] = $replits;
		}
	    return $response->withJson($cohorts);
	});
	
	//get all replits from a cohort
	$api->get('/cohort/{cohort_slug}', function (Request $request, Response $response, array $args) use ($api) {
		if(empty($args['cohort_slug'])) throw new Exception('Invalid param cohort_slug');
		try{
			$replits = $api->db['json']->getJsonByName($args['cohort_slug']);
			
			$cohort = BCWrapper::getCohort(['cohort_id' => $args['cohort_slug']]);
			$profileSlug = $cohort ?  $cohort->profile_slug : null;
			
			$baseReplits = ReplitFunctions::getTemplates($profileSlug);
			foreach($baseReplits as $brepl){
				if(!empty($brepl->base)){
					if(empty($replits[$brepl->slug])) $replits[$brepl->slug] = $brepl->base;
				}
			} 
		}
		catch(Exception $e){
			throw new Exception('The Cohort has no Replit\'s or does not exists', 404);
		}
	    return $response->withJson($replits);
	});
	
	//update replits of a cohort
	$api->post('/cohort/{cohort_slug}', function (Request $request, Response $response, array $args) use ($api) {
		if(empty($args['cohort_slug'])) throw new Exception('Invalid param cohort_slug');
		$originalCohort = null;
		try{
			$originalCohort = $api->db['json']->getJsonByName($args['cohort_slug']);
		}
		catch(Exception $e){}

		$cohort = BCWrapper::getCohort(['cohort_id' => $args['cohort_slug']]);
		if(!$cohort) throw new Exception('The cohort was not found on the Breathecode API', 400);
		
		$templatesPDO = new JsonPDO('_templates/','{}',false);
		$templates = $templatesPDO->getJsonByName($cohort->profile_slug);
			
		$data = $request->getParsedBody();
		if(!is_array($data)) throw new Exception('The body must be an object with all the replits of the cohort');
		
		foreach($templates as $replTemp) if(!isset($data[$replTemp->slug])) 
			throw new Exception('The replits are not following the same template, a replit class was expected for '.$replTemp->slug.' but was not found');

		if($originalCohort) $api->db['json']->toFile($args['cohort_slug'])->save($data);
		else $api->db['json']->toNewFile($args['cohort_slug'])->save($data);
		
	    return $response->withJson($data);
	})->add($api->auth());
	
	return $api;
};
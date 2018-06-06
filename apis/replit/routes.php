<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
function addAPIRoutes($api){
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
			
			$templatesPDO = new JsonPDO('base-replits.json','[]',false);
			$baseReplits = $templatesPDO->getAllContent();
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
	
	
	return $api;
}
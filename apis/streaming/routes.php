<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper;

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

function addAPIRoutes($api){
	
	$api->addTokenGenerationPath();
	
	//get all replits from a cohort
	$api->get('/cohort/{cohort_slug}', function (Request $request, Response $response, array $args) use ($api) {
		if(empty($args['cohort_slug'])) throw new Exception('Invalid param cohort_slug');
		try{
			$streaming = $api->db['json']->getJsonByName($args['cohort_slug']);
			$cohort = BCWrapper::getCohort(['cohort_id' => $args['cohort_slug']]);
			if(!$cohort) throw new Exception('The cohort was not found on the Breathecode API', 400);
			
			$streaming["player"] = StreamingFunctions::getStreamingLink($streaming["it"], $cohort);
			$streaming["iframe"] = StreamingFunctions::getIframeLink($streaming["it"], $cohort);
			$streaming["rtmp"] = StreamingFunctions::getRTMPLink($streaming["rtmp"]);
		}
		catch(Exception $e){
			if($e->getCode() == 400) throw $e;
			else throw new Exception('The Cohort has no streaming information or does not exists', 404);
		}
	    return $response->withJson($streaming);
	});

	return $api;
}
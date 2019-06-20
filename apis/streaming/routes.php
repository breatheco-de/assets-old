<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper;

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

return function($api){

	$api->addTokenGenerationPath();

	//get cohort streaming info
	$api->get('/cohort/all', function (Request $request, Response $response, array $args) use ($api) {

        $streaming = $api->db['json']->getAllContent();
        $cohorts = [];
        foreach($streaming as $key => $temp){
            $temp['slug'] = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($key));
            $cohorts[] = $temp;
        }

        return $response->withJson($cohorts);
	})->add($api->auth());

	//get cohort streaming info
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

	// update cohort streaming info
	$api->post('/cohort/{cohort_slug}', function (Request $request, Response $response, array $args) use ($api) {
		// @TODO: Add a new cohort from Stream Video Provider
	})->add($api->auth());

	//get cohort videos
	$api->get('/cohort/{cohort_slug}/videos', function (Request $request, Response $response, array $args) use ($api) {
		if(empty($args['cohort_slug'])) throw new Exception('Invalid param cohort_slug');

		$streaming = $api->db['json']->getJsonByName($args['cohort_slug']);

		StreamingFunctions::connect();
		return $response->withJson(StreamingFunctions::getVideosFromPlaylist([
			"channel_ref" => $streaming["playlist"],
			"video_source" => "ondemand"
		]));

	});

	$api->get('/playlists', function (Request $request, Response $response, array $args) use ($api) {
		StreamingFunctions::connect();
		return $response->withJson(StreamingFunctions::getPlaylists());
	});

	//used for SVP webhook url (optional)
	$api->get('/hook', function (Request $request, Response $response, array $args) use ($api) {

		$logs = $api->db['json']->getJsonByName('_svp_log');
		if(count($_GET) == 0) return $response->withJson($logs);

		array_push($logs, $_GET);
		$api->db['json']->toFile('_svp_log')->save($logs);

	    return $response->withJson($logs);
	});

	return $api;
};
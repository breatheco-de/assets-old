<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carbon\Carbon;
use \BreatheCode\BCWrapper;

require('../../vendor_static/ActiveCampaign/ACAPI.php');

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

\AC\ACAPI::start(AC_API_KEY);
\AC\ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);

function addAPIRoutes($api){

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
		$content = $api->db['sqlite']->event()->fetchAll();
	    return $response->withJson($content);
	});
	
	$api->get('/{event_id}', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['event_id'])) throw new Exception('Invalid param event_id', 500);
		
		$row = $api->db['sqlite']->event()->fetch($args['event_id']);

		return $response->withJson($row);	
	});
	
	$api->post('/{event_id}', function(Request $request, Response $response, array $args) use ($api) {
		//TODO: update event
		//return $response->withJson('');	
	});
	
	$api->delete('/{event_id}', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['event_id'])) throw new Exception('Invalid param event_id', 500);
		
		$row = $api->db['sqlite']->event()->fetch($args['event_id']);
		if($row) $row->delete();
		else throw new Exception('Event not found');
		
		return $response->withJson([ "code" => 200 ]);	
	});

	$api->put('/', function(Request $request, Response $response, array $args) use ($api) {
        $parsedBody = $request->getParsedBody();
        
        $desc = $api->validate($parsedBody,'description')->bigString();
        $title = $api->validate($parsedBody,'title')->smallString();
        $url = $api->validate($parsedBody,'url')->url();
        $capacity = $api->validate($parsedBody,'capacity')->int();
        $logo = $api->validate($parsedBody,'logo_url')->url();
        $private = $api->validate($parsedBody,'invite_only')->bool();
        
        $props = [
			'description' => $desc,
			'title' => $title,
			'url' => $url,
			'capacity' => $capacity,
			'logo_url' => $logo,
			'invite_only' => $private,
			'created_at' => date("Y-m-d H:i:s")
		];
		$row = $api->db['sqlite']->createRow('event', $props);
		$row->save();
		
        return $response->withJson($row);
	});
	
	return $api;
}
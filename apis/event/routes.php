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

	$api->addTokenGenerationPath();

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
		
		$content = $api->db['sqlite']->event();
		if(isset($_GET['type'])) $content = $content->where('type',$_GET['type']);
		if(isset($_GET['location'])) $content = $content->where('location_slug',$_GET['location']);
		if(isset($_GET['lang'])) $content = $content->where('lang',$_GET['lang']);
		$content = $content->orderBy( 'event_date', 'DESC' )->fetchAll();
	    
	    return $response->withJson($content);
	});
	
	$api->get('/{event_id}', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['event_id'])) throw new Exception('Invalid param event_id', 400);
		
		$row = $api->db['sqlite']->event()->fetch($args['event_id']);

		return $response->withJson($row);	
	});
	
	$api->post('/{event_id}', function(Request $request, Response $response, array $args) use ($api) {
		if(empty($args['event_id'])) throw new Exception('Invalid param event_id', 400);
		
		$event = $api->db['sqlite']->event()->fetch($args['event_id']);
        
        $parsedBody = $request->getParsedBody();
        $desc = $api->optional($parsedBody,'description')->bigString();
        if($desc) $event->description = $desc;
        
        $title = $api->optional($parsedBody,'title')->smallString();
        if($title) $event->title = $title;
        
        $url = $api->optional($parsedBody,'url')->url();
        if($url) $event->url = $url;
        
        $capacity = $api->optional($parsedBody,'capacity')->int();
        if($capacity) $event->capacity = $capacity;
        
        $logo = $api->optional($parsedBody,'logo_url')->url();
        if($logo) $event->logo_url = $logo;
        
        $private = $api->optional($parsedBody,'invite_only')->bool();
        if($private) $event->invite_only = $private;
        
        $event->save();

		return $response->withJson($event);
	})
		->add($api->auth());
	
	$api->delete('/{event_id}', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['event_id'])) throw new Exception('Invalid param event_id', 400);
		
		$row = $api->db['sqlite']->event()->fetch($args['event_id']);
		if($row) $row->delete();
		else throw new Exception('Event not found');
		
		return $response->withJson([ "code" => 200 ]);	
	})
		->add($api->auth());

	$api->put('/', function(Request $request, Response $response, array $args) use ($api) {
        $parsedBody = $request->getParsedBody();
        
        $desc = $api->validate($parsedBody,'description')->bigString();
        $title = $api->validate($parsedBody,'title')->smallString();
        $url = $api->validate($parsedBody,'url')->url();
        $capacity = $api->validate($parsedBody,'capacity')->int();
        $logo = $api->validate($parsedBody,'logo_url')->url();
        $type = $api->validate($parsedBody,'type')->smallString();
        $city = $api->validate($parsedBody,'city_slug')->smallString();
        $location = $api->validate($parsedBody,'location_slug')->smallString();
        $lang = $api->validate($parsedBody,'lang')->enum(['en','es']);
        $banner = $api->validate($parsedBody,'banner_url')->url();
        $address = $api->validate($parsedBody,'address')->smallString();
        $date = $api->validate($parsedBody,'event_date')->date();
        $private = $api->validate($parsedBody,'invite_only')->bool();
        
        $props = [
			'type' => EventFunctions::getType($type),
			'description' => $desc,
			'title' => $title,
			'url' => $url,
			'capacity' => $capacity,
			'logo_url' => $logo,
			'location_slug' => $location,
			'city_slug' => $city,
			'lang' => $lang,
			'banner_url' => $banner,
			'address' => $address,
			'invite_only' => $private,
			'event_date' => DateTime::createFromFormat('Y-m-d H:i:s', $date),
			'created_at' => date("Y-m-d H:i:s")
		];
		$row = $api->db['sqlite']->createRow('event', $props);
		$row->save();
		
        return $response->withJson($row);
	})
		->add($api->auth());

	$api->get('/{event_id}/checkin', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['event_id'])) throw new Exception('Invalid param event_id', 400);
		
		$row = $api->db['sqlite']->event_checking()->where( 'event_id', $args['event_id'] )->fetchAll();

		return $response->withJson($row);	
	});
	
	$api->put('/{event_id}/checkin', function(Request $request, Response $response, array $args) use ($api) {
        
        if(empty($args['event_id'])) throw new Exception('Invalid param event_id', 400);
        
        $parsedBody = $request->getParsedBody();
        $email = $api->validate($parsedBody,'email')->email();
        
        $contact = \AC\ACAPI::getContactByEmail($email);
        if(empty($contact)) throw new Exception('The user is not registered into Active Campaign', 400);
        
        $props = [
			'event_id' => $args['event_id'],
			'email' => $email,
			'created_at' => date("Y-m-d H:i:s")
		];
		
		$row = $api->db['sqlite']->createRow('event_checking', $props);
		$row->save();
		
		\AC\ACAPI::trackEvent($email, 'public_event_attendance');
		
        return $response->withJson($row);
	})
		->add($api->auth());
	
	return $api;
}
<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carbon\Carbon;
use BreatheCode\BCWrapper as BC;

require('../../vendor_static/ActiveCampaign/ACAPI.php');
require('../BreatheCodeLogger.php');

BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

\AC\ACAPI::start(AC_API_KEY);
\AC\ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);

function addAPIRoutes($api){

	$api->addTokenGenerationPath();

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
		
		$content = $api->db['sqlite']->event();
		if(isset($_GET['type'])) $content = $content->where('type',explode(",",$_GET['type']));
		if(isset($_GET['location'])) $content = $content->where('location_slug',explode(",",$_GET['location']));
		if(isset($_GET['lang'])) $content = $content->where('lang',explode(",",$_GET['lang']));
		if(isset($_GET['status'])){
			if($_GET['status']=='upcoming') {
				$content = $content->where('status','published');
				$content = $content->orderBy( 'event_date', 'DESC' )->fetchAll();
				$content = array_filter($content, function($evt){
					return ($evt->event_date >= date("Y-m-d"));
				});
			} else if($_GET['status']=='past') {
				$content = $content->where('status','published');
				$content = $content->orderBy( 'event_date', 'DESC' )->fetchAll();
				$content = array_filter($content, function($evt){
					return ($evt->event_date < date("Y-m-d"));
				});
			} else {
				$content = $content->where('status',explode(",",$_GET['status']));
				$content = $content->orderBy( 'event_date', 'DESC' )->fetchAll();
			}
		} 
	    return $response->withJson($content);
	});
	
	$api->get('/redirect', function (Request $request, Response $response, array $args) use ($api) {
		
		$content = $api->db['sqlite']->event();
		if(isset($_GET['type'])) $content = $content->where('type',explode(",",$_GET['type']));
		if(isset($_GET['location'])) $content = $content->where('location_slug',explode(",",$_GET['location']));
		if(isset($_GET['lang'])) $content = $content->where('lang',explode(",",$_GET['lang']));
		
		if(isset($_GET['status'])){
			if($_GET['status']=='upcoming') {
				$content = $content->where('status','published');
				$content = $content->orderBy( 'event_date', 'DESC' )->fetchAll();
				
				$content = array_filter($content, function($evt){
					return ($evt->event_date >= date("Y-m-d"));
				});
			}
			else if($_GET['status']=='past') {
				$content = $content->where('status','published');
				$content = $content->orderBy( 'event_date', 'DESC' )->fetchAll();
				$content = array_filter($content, function($evt){
					return ($evt->event_date < date("Y-m-d"));
				});
			}
		} 

		if(!is_array($content)) $content = $content->orderBy( 'event_date', 'DESC' )->fetchAll();
		
		if(isset($content[0])) return $response->withRedirect($content[0]->url); 
		else{
			$fallback = (isset($_GET['fallback'])) ? $_GET['fallback'] : null;
			//$api->sendMail("a@4geeks.us", "Broken redirect for events", "The following query returned no events: ".print_r($_GET));
			if($fallback) return $response->withRedirect($fallback)->withStatus(302); 
			else return $response->withStatus(404); 
		} 
	});
	
	$api->get('/{event_id}', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['event_id'])) throw new Exception('Invalid param event_id', 400);
		
		$row = $api->db['sqlite']->event()->where('id',$args['event_id'])->fetch();

		return $response->withJson($row);	
	});
	
	$api->post('/{event_id}', function(Request $request, Response $response, array $args) use ($api) {
		if(empty($args['event_id'])) throw new Exception('Invalid param event_id', 400);
		
		$event = $api->db['sqlite']->event()->where('id',$args['event_id'])->fetch();
        
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
        
        $val = $api->optional($parsedBody,'invite_only')->bool();
        if($val) $event->invite_only = $val;
        
        $val = $api->optional($parsedBody,'event_date')->date();
        if($val) $event->event_date = $val;
        
        $val = $api->optional($parsedBody,'type')->enum(EventFunctions::$types);
        if($val) $event->type = $val;
        
        $val = $api->optional($parsedBody,'status')->enum(EventFunctions::$status);
        if($val) $event->status = $val;
        
        $val = $api->optional($parsedBody,'address')->smallString();
        if($val) $event->address = $val;
        
        $val = $api->optional($parsedBody,'location_slug')->slug();
        if($val) $event->location_slug = $val;
        
        $val = $api->optional($parsedBody,'lang')->slug();
        if($val) $event->lang = $val;
        $val = $api->optional($parsedBody,'city_slug')->slug();
        if($val) $event->city_slug = $val;
        $val = $api->optional($parsedBody,'banner_url')->url();
        if($val) $event->banner_url = $val;
        
        $event->save();

		return $response->withJson($event);
	})->add($api->auth());
	
	$api->delete('/{event_id}', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['event_id'])) throw new Exception('Invalid param event_id', 400);
		
		$row = $api->db['sqlite']->event()->where('id',$args['event_id'])->fetch();
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
        $logo = $api->optional($parsedBody,'logo_url')->url();
        $type = $api->validate($parsedBody,'type')->enum(EventFunctions::$types);
        $city = $api->validate($parsedBody,'city_slug')->slug();
        $location = $api->validate($parsedBody,'location_slug')->slug();
        $lang = $api->validate($parsedBody,'lang')->enum(['en','es']);
        $banner = $api->validate($parsedBody,'banner_url')->url();
        $address = $api->validate($parsedBody,'address')->smallString();
        $date = $api->validate($parsedBody,'event_date')->date();
        $val = $api->validate($parsedBody,'invite_only')->bool();
        
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
			'status' => EventFunctions::getStatus('draft'),
			'banner_url' => $banner,
			'address' => $address,
			'invite_only' => $val,
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
		
		$event = $api->db['sqlite']->event()->where('id',$args['event_id'])->fetch();
		$user = BC::getUser(['user_id' => urlencode($email)]);
        BreatheCodeLogger::logActivity([
            'slug' => 'public_event_attendance',
            'user' => ($user) ? $user : $email,
            'data' => $event->title
        ]);
		
        return $response->withJson($row);
	})
		->add($api->auth());
	
	return $api;
}
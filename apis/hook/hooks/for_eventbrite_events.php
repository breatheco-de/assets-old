<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use jamiehollern\eventbrite\Eventbrite;
use BreatheCode\BCWrapper as BC;

BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

function addEventbriteRoutes($api){
	
	$api->post('/eventbrite', function (Request $request, Response $response, array $args) use ($api) {
        
        $eventbrite = new Eventbrite(EVENTBRITE_KEY);
        
        $eventbriteRequest = $request->getParsedBody();
        $body = $eventbriteRequest['config'];
        $api->log(SlimAPI::$INFO, 'New hook call:', $body);
        
        if(empty($body['user_id'])) throw new Exception('Invalid body request, missing user_id');
        
        switch($body['action']){
          case "barcode.checked_in":
              $eResp = $eventbrite->call('GET', "users/".$body['user_id']);
              if($eResp['code'] === 200){
                HookFunctions::checking($eResp['body']);
                return $response->withJSON($eResp['body']);
              }
              else{
                throw new Exception($response['body']['error_description']);
              }
          break;
          case "order.placed":
            //TODO: register in active campaign when a someone RSVP for an event
          break;
          //case "event.published":
          case "event.created":
            $eResp = $eventbrite->call('GET', "users/".$body['user_id'].'/owned_events/?order_by=created_desc');
              if($eResp['code'] === 200){
                HookFunctions::create_event($eResp['body']['events'][0]);
                return $response->withJSON($eResp['body']['events'][0]);
              }
              else{
                throw new Exception($response['body']['error_description']);
              }
          break;
          case "event.unpublished":
            //TODO: remove eventbrite unpublished events
          break;
          case "test":
            //TODO: remove eventbrite unpublished events
            return $response->withJSON($body);
          break;
        }
	});
		
	$api->post('/eventbrite/sync', function (Request $request, Response $response, array $args) use ($api) {
        
        $parsedBody = $request->getParsedBody();
        if(!empty($parsedBody['email'])) $userEmail = $parsedBody['email'];
        else if(isset($parsedBody['contact']['email'])) $userEmail = $parsedBody['contact']['email'];
        else throw new Exception('Please specify the user email');
        
         \AC\ACAPI::start(AC_API_KEY);
        $contact = \AC\ACAPI::getContactByEmail($userEmail);
        $result = \AC\ACAPI::updateContactFields($contact, 0);

        return $response->withJson('ok');
        
        return $response->withJSON($args);
	});

	return $api;
}
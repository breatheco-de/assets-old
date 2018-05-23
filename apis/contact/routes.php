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

	$api->get('/{email}', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['email'])) throw new Exception('Invalid param email', 400);
		
        $contact = \AC\ACAPI::getContactByEmail($args['email']);
        if(empty($contact)) $response->withJson("The user was not found in active campaign",400);

		return $response->withJson($contact);	
	});
	
	return $api;
}